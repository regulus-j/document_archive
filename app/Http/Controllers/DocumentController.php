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

class DocumentController extends Controller
{
    function __construct()
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
            ->with('i', (request()->input('page', 1) - 1) * 5);
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
                'C:\Users\user\Dependencies\poppler-24.08.0\Library\bin\pdftotext.exe'
            ))
            ->setPdf($path)
            ->text();
    
            if (empty($pdfContent)) {
                // If no content extracted, try using shell_exec as fallback
                $outputFile = storage_path('app/temp/pdf_' . time() . '.txt');
                $command = '"C:\Users\user\Dependencies\poppler-24.08.0\Library\bin\pdftotext.exe" "' . str_replace('/', '\\', $path) . '" "' . str_replace('/', '\\', $outputFile) . '"';
                
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
        $document->delete();

        return redirect()->route('documents.index')
                        ->with('success','Document deleted successfully');
    }
}
