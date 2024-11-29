<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Controller\Controller\User;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Exception;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use App\Models\Team;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:document-list|document-create|document-edit|document-delete', ['only' => ['index','show']]);
        $this->middleware('permission:document-create', ['only' => ['create','store']]);
        $this->middleware('permission:document-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:document-delete', ['only' => ['destroy']]); 
    }

        /**
     * Display a listing of the documents.
     */
    public function index(): View
    {
        $documents = Document::with('user')->latest()->paginate(5);

        return view('documents.index',compact('documents'))
            ->with(['i', (request()->input('page', 1) - 1) * 5,
                        'teams' => Team::all()]);
    }

    /**
     * Search for documents using text input or image upload.
     */
    public function search(Request $request)
    {
        $request->validate([
            'text' => 'nullable|string|max:255',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:10240'
        ]);
    
        try {
            $searchText = $request->input('text', '');
            $content = '';
    
            // Check if the file is uploaded
            if ($request->hasFile('image')) {
                // Store the file and get its path
                $file = $request->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('documents', $fileName, 'public');
                $fullPath = storage_path('app/public/' . $filePath);
    
                if ($this->isImage($file)) {
                    // Create a temporary output file
                    $outputBase = storage_path('app/temp/ocr_' . time());
    
                    // Construct the command with explicit paths and double quotes
                    $command = '"C:\\Program Files\\Tesseract-OCR\\tesseract.exe" "' . str_replace('/', '\\', $fullPath) . '" "' . str_replace('/', '\\', $outputBase) . '" -l eng';
    
                    // Execute command
                    $output = shell_exec($command);
    
                    // Check if output file exists and read content
                    if (file_exists($outputBase . '.txt')) {
                        $content = file_get_contents($outputBase . '.txt');
                        unlink($outputBase . '.txt'); // Clean up temp file
    
                        if (empty($content)) {
                            throw new Exception('OCR processing failed - no text extracted');
                        }
                    } else {
                        throw new Exception('OCR processing failed - output file not created');
                    }
    
                    // Log the command and output for debugging
                    \Log::info('OCR Command: ' . $command);
                    \Log::info('OCR Output: ' . ($output ?? 'No output'));
    
                    // Combine text input and OCR content for search
                    $searchText .= ' ' . $content;
    
                    // Perform search using MySQL MATCH() in NATURAL LANGUAGE mode
                    $documents = Document::whereRaw('MATCH(content) AGAINST(? IN NATURAL LANGUAGE MODE)', [$searchText])->get();
                }
            } else {
                // Perform search using only text input in BOOLEAN mode
                $documents = Document::whereRaw('MATCH(content) AGAINST(? IN NATURAL LANGUAGE MODE)', [$searchText])->get();
            }
    
            return view('documents.index',compact('documents'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    
        } catch (Exception $e) {
            \Log::error('Search processing error: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Error processing search: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Show the form for creating a new document.
     */
    public function create(): View
    {
        return view('documents.create');
    }

    // private function isImage($file)  {
    //     $imagen = getimagesize($file);
    //     return $imagen !== false;
    // }

    /**
     * Store a newly created document in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'upload' => 'required|file|mimes:jpeg,png,jpg,gif,pdf,docx|max:10240'
        ]);
    
        try {
            // Store the file and get its path
            $file = $request->file('upload');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');
            $fullPath = storage_path('app/public/' . $filePath);
            
            // Initialize content variable
            $content = '';
    
            if ($this->isImage($file)) {
                // Create a temporary output file
                $outputBase = storage_path('app/temp/ocr_' . time());
                
                // Construct the command with explicit paths and double quotes
                $command = '"C:\\Program Files\\Tesseract-OCR\\tesseract.exe" "' . str_replace('/', '\\', $fullPath) . '" "' . str_replace('/', '\\', $outputBase) . '" -l eng';
                
                // Execute command
                $output = shell_exec($command);
                
                // Check if output file exists and read content
                if (file_exists($outputBase . '.txt')) {
                    $content = file_get_contents($outputBase . '.txt');
                    unlink($outputBase . '.txt'); // Clean up temp file
                    
                    if (empty($content)) {
                        throw new Exception('OCR processing failed - no text extracted');
                    }
                } else {
                    throw new Exception('OCR processing failed - output file not created');
                }
                
                // Log the command and output for debugging
                \Log::info('OCR Command: ' . $command);
                \Log::info('OCR Output: ' . ($output ?? 'No output'));
                
            } else {
                $extension = strtolower($file->getClientOriginalExtension());
                
                switch ($extension) {
                    case 'pdf':
                        $content = $this->extractPdfContent($fullPath);
                        break;
                        
                    case 'docx':
                        $content = $this->extractDocxContent($fullPath);
                        break;
                }
            }
    
            // Create document record
            Document::create([
                'title' => $request->title,
                'uploader' => auth()->user()->id,
                'description' => $request->description,
                'content' => $content,
                'path' => $filePath
            ]);
    
            return redirect()->route('documents.index')
                            ->with('success', 'Document created successfully.');
    
        } catch (Exception $e) {
            \Log::error('Document processing error: ' . $e->getMessage());
            return redirect()->back()
                            ->with('error', 'Error processing document: ' . $e->getMessage())
                            ->withInput();
        }
    }
    
    private function isImage($file): bool
    {
        $mimeType = $file->getMimeType();
        return strpos($mimeType, 'image/') === 0;
    }

    private function extractPdfContent(string $path): string
    {
        try {
            // Use the path directly in the constructor of Pdf
            $pdfContent = (new Pdf(
                env('POPPLER_PATH')
            ))
            ->setPdf($path)
            ->text();
    
            if (empty($pdfContent)) {
                // If no content extracted, try using shell_exec as fallback
                $outputFile = storage_path('app/temp/pdf_' . time() . '.txt');
                $command = env('POPPLER_PATH') . ' ' . str_replace('/', '\\', $path) . ' ' . str_replace('/', '\\', $outputFile);
                
                // Log the command for debugging
                \Log::info('PDF Command: ' . $command);
                
                $output = shell_exec($command);
                \Log::info('Shell exec output: ' . ($output ?? 'No output'));
                
                if (file_exists($outputFile)) {
                    $pdfContent = file_get_contents($outputFile);
                    unlink($outputFile); // Clean up
                }
            }
    
            if (empty($pdfContent)) {
                throw new Exception('PDF processing failed - no text extracted');
            }
    
            return $pdfContent;
    
        } catch (Exception $e) {
            \Log::error('PDF processing error: ' . $e->getMessage());
            \Log::error('PDF path: ' . $path);
            throw new Exception('PDF processing failed: ' . $e->getMessage());
        }
    }

    private function extractDocxContent(string $path): string
    {
        // Using PHPWord to extract text from DOCX
        $phpWord = IOFactory::load($path);
        $content = '';
        
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $content .= $element->getText() . "\n";
                }
            }
        }
        
        return $content;
    }

    /**
     * Display the specified document.
     */
    public function show(Document $document): View
    {
        return view('documents.show',compact('document'));
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(Document $document): View
    {
        return view('documents.edit',compact('document'));
    }

    /**
     * Update the specified document in storage.
     */
    public function update(Request $request, Document $document): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $document->update($request->all());

        return redirect()->route('documents.index')
                        ->with('success','Document updated successfully');
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy(Document $document): RedirectResponse
    {
        Storage::disk('public')->delete($document->path);
        $document->delete();

        return redirect()->route('documents.index')
                        ->with('success','Document deleted successfully');
    }

    public function uploadImage(Request $request)
    {
        $data = $request->input('image');

        // Decode the base64 image
        list($type, $data) = explode(';', $data);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);

        // Generate a unique filename
        $filename = 'uploads/' . uniqid() . '.png';

        // Store the image
        Storage::put($filename, $data);

        return Response::json(['success' => 'Image uploaded successfully', 'filename' => $filename, 'path' => asset('storage/' . $filename)]);
    }

public function downloadFile($id)
{
    $document = Document::findOrFail($id);
    $filePath = storage_path('app/public/' . $document->path);

    if (!file_exists($filePath)) {
        return redirect()->back()->with('error', 'File not found.');
    }

    return response()->download($filePath);
}
}
