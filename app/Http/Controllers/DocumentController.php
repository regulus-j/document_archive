<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;
use PhpOffice\PhpWord\IOFactory;
use Exception;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\DocumentTrackingNumber;
use App\Models\Office;
use App\Models\DocumentAudit;
use App\Models\DocumentCategory;
use App\Models\DocumentTransaction;

class DocumentController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:document-list|document-create|document-edit|document-delete', ['only' => ['index','show']]);
    //     $this->middleware('permission:document-create', ['only' => ['create','store']]);
    //     $this->middleware('permission:document-edit', ['only' => ['edit','update']]);
    //     $this->middleware('permission:document-delete', ['only' => ['destroy']]); 
    // }

    /**
     * Display a listing of the documents.
     */
    public function index(): View
    {
        if (auth()->user()->hasRole('Admin')) {
            $documents = Document::with(['user', 'status', 'transaction.fromOffice', 'transaction.toOffice'])->latest()->paginate(5);
        } else {
            $userOfficeIds = auth()->user()->offices->pluck('id')->toArray();
            
            $documents = Document::with(['user', 'status', 'transaction.fromOffice', 'transaction.toOffice'])
                ->whereHas('user.offices', function($query) use ($userOfficeIds) {
                    $query->whereIn('offices.id', $userOfficeIds);
                })
                ->latest()
                ->paginate(5);
        }

        $auditLogs = DocumentAudit::paginate(15);
    
        return view('documents.index', array_merge([
            'documents' => $documents,
            'i' => (request()->input('page', 1) - 1) * 5
        ], compact('auditLogs')));
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

            $query = Document::with(['user', 'status', 'transaction.fromOffice', 'transaction.toOffice']);

            if (!auth()->user()->hasRole('Admin')) {
                $userOfficeIds = auth()->user()->offices->pluck('id')->toArray();
                $query->whereHas('user.offices', function($q) use ($userOfficeIds) {
                    $q->whereIn('offices.id', $userOfficeIds);
                });
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('documents', $fileName, 'public');
                $fullPath = storage_path("app/public/{$filePath}");

                if ($this->isImage($file)) {
                    $outputBase = storage_path("app/temp/ocr_{time()}");
                    $command = '"C:\\Program Files\\Tesseract-OCR\\tesseract.exe" "' . str_replace('/', '\\', $fullPath) . '" "' . str_replace('/', '\\', $outputBase) . '" -l eng';
                    $output = shell_exec($command);

                    if (file_exists("{$outputBase}.txt")) {
                        $content = file_get_contents("{$outputBase}.txt");
                        unlink("{$outputBase}.txt");
                        $searchText .= " {$content}";
                    }
                }
            }

            if (!empty($searchText)) {
                $query->whereRaw('MATCH(content) AGAINST(? IN NATURAL LANGUAGE MODE)', [$searchText]);
            }

            $documents = $query->latest()->paginate(5);
            $auditLogs = DocumentAudit::paginate(15);

            return view('documents.index', array_merge([
                'documents' => $documents,
                'i' => (request()->input('page', 1) - 1) * 5
            ], compact('auditLogs')));

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
        $offices = Office::all();
        $tracking = $this->generateTrackingNumber();
        $categories = DocumentCategory::all()->pluck('category', 'id');
        return view('documents.create', compact('offices', 'tracking', 'categories'));    
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the request
        $request->validate([
            'tracking_number' => 'required|string|unique:document_trackingnumbers,tracking_number',
            'title' => 'required|string|max:255',
            'description' => 'required',
            'classification' => 'required|string',
            'from_office' => 'required|exists:offices,id',
            'to_office' => 'required|exists:offices,id',
            'remarks' => 'nullable|string|max:250',
            'upload' => 'required|file|mimes:jpeg,png,jpg,gif,pdf,docx|max:10240', // 10MB max
        ]);

        try {
            // Handle file upload
            $file = $request->file('upload');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');
            // $fullPath = Storage::path('public/' . $filePath);
            
            // Extract content based on file type
            // $content = '';
            // In the store method, replace the OCR section with:
            // if ($this->isImage($file)) {
            //     // Configure Tesseract with proper path
            //     $command = '"' . env('TESSERACT_PATH', 'C:\Program Files\Tesseract-OCR\tesseract.exe') . '" "' . $fullPath . '" stdout';
            //     $content = shell_exec($command);
                
            //     if (empty($content)) {
            //         \Log::warning('OCR produced no output for file: ' . $fileName);
            //         $content = ''; // Fallback to empty content
            //     }
                
            //     // Log the command and output for debugging
            //     \Log::info('OCR Command: ' . $command);
            //     \Log::info('OCR Output: ' . ($content ?? 'No output'));
            // }

            // Create document record

            $document = Document::create([
                'title' => $request->title,
                'uploader' => auth()->id(),
                'description' => $request->description,
                'path' => $filePath,
                'remarks' => $request->remarks ?? null,
            ]);

            $document->categories()->attach([$request->classification]); 

            $document->status()->create([
                'status' => 'pending'
            ]);

            // Create tracking number record
            DocumentTrackingNumber::create([
                'doc_id' => $document->id,
                'tracking_number' => $request->tracking_number,
            ]);

            DocumentTransaction::create([
                'doc_id' => $document->id,
                'from_office' => $request->from_office,
                'to_office' => $request->to_office
            ]);

            // Log document creation
            $this->logDocumentAction($document, 'created', 'new', 'Document uploaded');

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
        $this->logDocumentAction($document, 'viewed');
        $document->load(['trackingNumber', 'categories', 'transaction.fromOffice', 'transaction.toOffice', 'status']);
        $auditLogs = DocumentAudit::where('document_id', $document->id)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('documents.show',compact('document', 'auditLogs'));
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(Document $document): View
    {
        $userOffice = auth()->user()->offices->pluck('name', 'id');
        $offices = Office::all();
        $categories = DocumentCategory::all()->pluck('category', 'id');
        return view('documents.edit',compact('document', 'categories', 'offices', 'userOffice'));
    }

    /**
     * Update the specified document in storage.
     */
    public function update(Request $request, Document $document): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'classification' => 'required|string',
            'from_office' => 'required|exists:offices,id',
            'to_office' => 'required|exists:offices,id',
            'remarks' => 'nullable|string|max:250',
            'upload' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,docx|max:10240',
        ]);

        try {
            $updateData = [
            'title' => $request->title,
            'description' => $request->description,
            'remarks' => $request->remarks ?? null,
            ];

            if ($request->hasFile('upload')) {
            // Delete old file
            Storage::disk('public')->delete($document->path);
            
            // Store new file
            $file = $request->file('upload');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');
            $updateData['path'] = $filePath;
            }

            $document->update($updateData);
            $document->categories()->sync([$request->classification]);
            
            DocumentTransaction::where('doc_id', $document->id)->update([
            'from_office' => $request->from_office,
            'to_office' => $request->to_office
            ]);

            $this->logDocumentAction($document, 'updated', null, 'Document information updated');

            return redirect()->route('documents.index')
                    ->with('success','Document updated successfully');

        } catch (Exception $e) {
            \Log::error('Document update error: ' . $e->getMessage());
            return redirect()->back()
            ->with('error', 'Error updating document: ' . $e->getMessage())
            ->withInput();
        }
        }

    /**
     * Remove the specified document from storage.
     */
    public function destroy(Document $document): RedirectResponse
    {
        $this->logDocumentAction($document, 'deleted');
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
        $this->logDocumentAction($document, 'downloaded');
        $filePath = storage_path('app/public/' . $document->path);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return response()->download($filePath);
    }

    function generateTrackingNumber($prefix = 'TRK', $length = 10) {
        do {
            // Define the characters to use for the random part
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $charactersLength = strlen($characters);
            $randomString = '';
        
            // Generate the random part of the tracking number
            for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
        
            // Combine the prefix with the random string and a timestamp
            $trackingNumber = $prefix . '-' . $randomString . '-' . time();

            // Check if this tracking number already exists
            $exists = DocumentTrackingNumber::where('tracking_number', $trackingNumber)->exists();
        } while ($exists);

        return $trackingNumber;
    }

    public function generateTrackingSlip($docid, $uploaderid)
    {
        $document = Document::findOrFail($docid);
        $uploader = User::findOrFail($uploaderid);


    }

    public function scanQr($image){

    }

    //auditing
    private function logDocumentAction($document, $action, $status = null, $details = null)
    {
        DocumentAudit::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'status' => $status,
            'details' => $details
        ]);
    }

    public function audit()
    {
        $auditLogs = DocumentAudit::with(['document', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('documents.audit', compact('auditLogs'));
    }

    public function showPending(): View
    {
        // Get the IDs of the offices associated with the authenticated user
        $userOfficeIds = auth()->user()->offices->pluck('id')->toArray();
    
        // Retrieve documents where the transaction's from_office or to_office matches the user's offices
        $documents = Document::with(['user', 'status', 'transaction.fromOffice', 'transaction.toOffice'])
            ->whereHas('transaction', function($query) use ($userOfficeIds) {
                $query->whereIn('from_office', $userOfficeIds)
                      ->orWhereIn('to_office', $userOfficeIds);
            })
            ->whereHas('status', function($query) {
                $query->whereIn('status', ['pending', 'received', 'released', 'terminal']);
            })
            ->latest()
            ->paginate(5);
    
        return view('documents.pending', [
            'documents' => $documents,
            'i' => (request()->input('page', 1) - 1) * 5,
        ]);
    }

    public function setReceived($id): RedirectResponse
    {
        try {
            $document = Document::findOrFail($id);
            $document->status()->update(['status' => 'received']);
            
            $this->logDocumentAction($document, 'status_changed', 'received', 'Document marked as received');

            return redirect()->back()->with('success', 'Document marked as received');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error updating document status: ' . $e->getMessage());
        }
    }

    public function confirmReleased($id)
    {
        $document = Document::with(['transaction.fromOffice', 'transaction.toOffice'])->findOrFail($id);

        return view('documents.releasing_form', compact('document'));    
    }

    public function setReleased($id){
        $document = Document::findOrFail($id);
        $document->status()->update(['status' => 'released']);
        
        $this->logDocumentAction($document, 'status_changed', 'released', 'Document marked as released');

        return redirect()->route('documents.pending')->with('success', 'Document marked as released');
    }
}
