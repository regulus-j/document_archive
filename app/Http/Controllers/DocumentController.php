<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentAttachment;
use App\Models\DocumentAudit;
use App\Models\DocumentCategory;
use App\Models\DocumentTrackingNumber;
use App\Models\DocumentTransaction;
use App\Models\DocumentWorkflow;
use App\Models\CompanyAccount;
use App\Models\Office;
use App\Models\User;
use chillerlan\QRCode\QRCode;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use PhpOffice\PhpWord\IOFactory;
use Spatie\PdfToText\Pdf;

class DocumentController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:document-list|document-create|document-edit|document-delete', ['only' => ['index', 'show']]);
    //     $this->middleware('permission:document-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:document-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:document-delete', ['only' => ['destroy']]);
    // }

    /**
     * Display a listing of the documents.
     */
    public function index(): View
    {
        if (auth()->user()->hasRole('company-admin')) {
            $documents = Document::with(['user', 'status', 'transaction.fromOffice', 'transaction.toOffice'])
                ->latest()
                ->paginate(5);
        } else {
            $userOfficeIds = auth()->user()->offices->pluck('id')->toArray();

            $documents = Document::with(['user', 'status', 'transaction.fromOffice', 'transaction.toOffice'])
                ->whereHas('user.offices', function ($query) use ($userOfficeIds) {
                    $query->whereIn('offices.id', $userOfficeIds);
                })
                ->latest()
                ->paginate(5);
        }

        $auditLogs = DocumentAudit::latest()->paginate(15);

        // Determine the recipients for each document
        $documentRecipients = [];
        foreach ($documents as $doc) {
            $workflows = DocumentWorkflow::with(['recipient', 'recipientOffice'])
                ->where('document_id', $doc->id)
                ->get();
            
            $recipients = collect();
            
            foreach ($workflows as $workflow) {
                // Add user recipients
                if ($workflow->recipient) {
                    $name = trim($workflow->recipient->first_name . ' ' . $workflow->recipient->last_name);
                    $recipients->push([
                        'name' => $name,
                        'type' => 'user',
                        'step_order' => $workflow->step_order
                    ]);
                }
                
                // Add office recipients
                if ($workflow->recipient_office && $workflow->recipientOffice) {
                    $recipients->push([
                        'name' => $workflow->recipientOffice->name,
                        'type' => 'office',
                        'step_order' => $workflow->step_order
                    ]);
                }
            }
            
            $documentRecipients[$doc->id] = $recipients;
        }

        return view('documents.index', compact('documents', 'auditLogs', 'documentRecipients'));
    }

    public function showArchive(Request $request): View
    {
        $search = $request->input('search');
        
        $query = Document::with(['user', 'status', 'transaction.fromOffice', 'transaction.toOffice'])
            ->whereHas('status', function($q) {
                $q->where('status', 'archived');
            });
        
        // Apply search if provided
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by user's office if not company admin
        if (!auth()->user()->hasRole('company-admin')) {
            $userOfficeIds = auth()->user()->offices->pluck('id')->toArray();
            $query->whereHas('user.offices', function ($q) use ($userOfficeIds) {
                $q->whereIn('offices.id', $userOfficeIds);
            });
        }
        
        $documents = $query->latest()->paginate(5);
        $auditLogs = DocumentAudit::paginate(15);

        return view('documents.archive', array_merge([
            'documents' => $documents,
            'i' => (request()->input('page', 1) - 1) * 5,
            'search' => $search,
        ], compact('auditLogs')));
    }

    //Storing the document
    public function uploadController(Request $request)
    {
        \Log::info('uploadController called');

        //upload to database
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'classification' => 'required|string',
            'from_office' => 'required|exists:offices,id',
            'remarks' => 'nullable|string|max:250',
            'upload' => 'required|file', // 10MB max
            'attachements.*' => 'file|mimes:jpeg,png,jpg,gif,pdf,docx|max:10240',
            'archive' => 'nullable|string',
            'forward' => 'nullable|string',
        ]);

        try {
            \Log::info('Starting document upload process');
            $companyId = auth()->user()->companies()->first()->id ?? 'default';
            $companyPath = $companyId;
            
            $file = $request->file('upload');
            $fileName = time() . '_' . $file->getClientOriginalName();
            \Log::info('Uploading file', ['fileName' => $fileName]);
            $filePath = $file->storeAs($companyPath . '/documents', $fileName, 'public');

            $document = Document::create([
                'title' => $request->title,
                'uploader' => auth()->id(),
                'description' => $request->description,
                'path' => $filePath,
                'remarks' => $request->remarks ?? null,
            ]);
            \Log::info('Document created', ['document_id' => $document->id]);

            $document->status()->create([
                'status' => 'pending',
            ]);
            \Log::info('Initial document status set to pending', ['document_id' => $document->id]);

            $tracking_number = $this->generateTrackingNumber($request->from_office, $request->classification);
            \Log::info('Generated tracking number', ['tracking_number' => $tracking_number]);

            // Create tracking number record
            DocumentTrackingNumber::create([
                'doc_id' => $document->id,
                'tracking_number' => $tracking_number,
            ]);
            \Log::info('Tracking number record created', ['document_id' => $document->id]);

            // Only create transaction if to_office is provided AND forwarding is enabled
            if ($request->has('to_office') && $request->forward == '1') {
                DocumentTransaction::create([
                    'doc_id' => $document->id,
                    'from_office' => $request->from_office,
                    'to_office' => $request->to_office,
                ]);
                \Log::info('Document transaction created', ['document_id' => $document->id]);
            }

            // Handle additional attachments if any
            if ($request->hasFile('attachments')) {
                \Log::info('Processing attachments');
                foreach ($request->file('attachments') as $attachment) {
                    $attachmentName = time() . '_' . $attachment->getClientOriginalName();
                    $companyPath = auth()->user()->companies()->first()->id ?? 'default';
                    \Log::info('Uploading attachment', ['attachmentName' => $attachmentName]);
                    $attachmentPath = $attachment->storeAs($companyPath . '/attachments', $attachmentName, 'public');

                    DocumentAttachment::create([
                        'document_id' => $document->id,
                        'filename' => $attachmentName,
                        'path' => $attachmentPath,
                    ]);
                    \Log::info('Attachment record created', ['document_id' => $document->id, 'attachmentName' => $attachmentName]);
                }
            }

            // Log document creation
            $this->logDocumentAction($document, 'created', 'pending', 'Document uploaded');
            \Log::info('Document upload action logged', ['document_id' => $document->id]);

            $data = $this->generateTrackingSlip($document->id, auth()->id(), $tracking_number);
            \Log::info('Tracking slip generated', ['document_id' => $document->id]);

            if ($request->archive == '1') {
                $document->status()->update(['status' => 'archived']);
                \Log::info('Document status updated to archived', ['document_id' => $document->id]);
            }
            
            if ($request->forward == '1') {
                \Log::info('Redirecting to forward route', ['document_id' => $document->id]);
                return redirect()->route('documents.forward', $document->id)
                    ->with('data', $data)
                    ->with('success', 'Document uploaded successfully');
            } else {
                \Log::info('Redirecting to index route', ['document_id' => $document->id]);
                return redirect()->route('documents.index')
                    ->with('data', $data)
                    ->with('success', 'Document uploaded successfully');
            }

        } catch (Exception $e) {
            \Log::error('Document processing error in uploadController: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error processing document: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function forwardDocument(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        
        // Get the companies this user belongs to
        $userCompanyIds = auth()->user()->companies()->pluck('company_id');
        
        // Get users from these companies
        $users = User::whereHas('companies', function($query) use ($userCompanyIds) {
            $query->whereIn('company_id', $userCompanyIds);
        })->where('id', '!=', auth()->id())->get(); // Exclude the current user
        
        // Get offices from these companies
        $offices = Office::whereIn('company_id', $userCompanyIds)->get();
        
        return view('documents.forward', compact('document', 'offices', 'users'));
    }

    public function searchByTr(Request $request)
    {
        // Validate the tracking number input
        $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        try {
            // Search for the document by tracking number
            $documentTracking = DocumentTrackingNumber::where('tracking_number', $request->tracking_number)
                ->with('document') // Load the related document
                ->firstOrFail();

            $document = $documentTracking->document;

            // Redirect to the document's show route
            return redirect()->route('documents.show', $document->id)
                ->with('success', 'Document found.');
        } catch (\Exception $e) {
            // Log the error and return an error message
            \Log::error('Error searching for tracking number: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Tracking number not found.');
        }
    }
    /**
     * Search for documents using text input or image upload.
     */
    public function search(Request $request)
    {
        $request->validate([
            'text' => 'nullable|string|max:255',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        try {
            $searchText = $request->input('text', '');
            $content = '';

            $query = Document::with(['user', 'status', 'transaction.fromOffice', 'transaction.toOffice']);

            if (!auth()->user()->hasRole('company-admin')) {
                $userOfficeIds = auth()->user()->offices->pluck('id')->toArray();
                $query->whereHas('user.offices', function ($q) use ($userOfficeIds) {
                    $q->whereIn('offices.id', $userOfficeIds);
                });
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('documents', $fileName, 'public');
                $fullPath = storage_path("app/public/temp/{$filePath}");

                if ($this->isImage($file)) {
                    $qrResult = $this->scanQr($fullPath);

                    if (!empty($qrResult)) {
                        // If QR code contains a tracking number
                        $documentTracking = DocumentTrackingNumber::where('tracking_number', $qrResult)->first();
                        
                        if ($documentTracking) {
                            $document = Document::find($documentTracking->doc_id);
                            if ($document) {
                                return redirect()->route('documents.show', $document->id)
                                    ->with('success', 'Document found by QR code.');
                            }
                        }
                    }
                }
            }

            if (!empty($searchText)) {
                $query->whereRaw('MATCH(content) AGAINST(? IN NATURAL LANGUAGE MODE)', [$searchText]);
            }

            $documents = $query->latest()->paginate(5);
            $auditLogs = DocumentAudit::paginate(15);

            // Determine the recipients for each document (same code as in index method)
            $documentRecipients = [];
            foreach ($documents as $doc) {
                $workflows = DocumentWorkflow::with(['recipient', 'recipientOffice'])
                    ->where('document_id', $doc->id)
                    ->get();
                
                $recipients = collect();
                
                foreach ($workflows as $workflow) {
                    // Add user recipients
                    if ($workflow->recipient) {
                        $name = trim($workflow->recipient->first_name . ' ' . $workflow->recipient->last_name);
                        $recipients->push([
                            'name' => $name,
                            'type' => 'user',
                            'step_order' => $workflow->step_order
                        ]);
                    }
                    
                    // Add office recipients
                    if ($workflow->recipient_office && $workflow->recipientOffice) {
                        $recipients->push([
                            'name' => $workflow->recipientOffice->name,
                            'type' => 'office',
                            'step_order' => $workflow->step_order
                        ]);
                    }
                }
                
                $documentRecipients[$doc->id] = $recipients;
            }

            // Return to the current page with search results
            return redirect()->back()->with([
                'documents' => $documents,
                'auditLogs' => $auditLogs,
                'documentRecipients' => $documentRecipients,
                'searchPerformed' => true,
                'searchText' => $searchText,
            ]);

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
        $company = CompanyAccount::where('user_id', auth()->id())->first();

        $users = $company ? $company->employees()->paginate(10) : collect();
        $offices = Office::all();

        $categories = [
            1 => 'Letter',
            2 => 'Memo',
            3 => 'Reports',
            4 => 'Proposal',
            5 => 'Presentation',
            6 => 'Others',
            
        ];

        return view('documents.create', compact('offices', 'categories', 'users'));
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'classification' => 'required|string',
            // 'from_office' => 'required|exists:offices,id',
            // 'to_office' => 'required|exists:offices,id',
            'remarks' => 'nullable|string|max:250',
            'upload' => 'required|file|mimes:jpeg,png,jpg,gif,pdf,docx|max:10240', // 10MB max
            'attachements.*' => 'file|mimes:jpeg,png,jpg,gif,pdf,docx|max:10240',
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
                'status' => 'pending',
            ]);
            $tracking_number = $this->generateTrackingNumber($request->from_office, $request->classification);

            // Create tracking number record
            DocumentTrackingNumber::create([
                'doc_id' => $document->id,
                'tracking_number' => $tracking_number,
            ]);

            DocumentTransaction::create([
                'doc_id' => $document->id,
                'from_office' => $request->from_office,
                'to_office' => $request->to_office,
            ]);

            // Handle additional attachments if any

            if ($request->hasFile('attachments')) {

                foreach ($request->file('attachments') as $attachment) {

                    $attachmentName = time() . '_' . $attachment->getClientOriginalName();

                    $attachmentPath = $attachment->storeAs('attachments', $attachmentName, 'public');

                    DocumentAttachment::create([

                        'document_id' => $document->id,

                        'filename' => $attachmentName,

                        'path' => $attachmentPath,

                    ]);
                }
            }

            // Log document creation
            $this->logDocumentAction($document, 'created', 'pending', 'Document uploaded');

            $data = $this->generateTrackingSlip($document->id, auth()->id(), $tracking_number);

            return redirect()->route('documents.index')
                ->with('data', $data)
                ->with('success', 'Document uploaded successfully');

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
        // Check if the user can access this document
        if (!auth()->user()->hasRole('super-admin') && 
            !auth()->user()->hasRole('company-admin') &&
            auth()->user()->id != $document->uploader &&
            !DocumentWorkflow::where('document_id', $document->id)
                ->where('recipient_id', auth()->user()->id)
                ->exists()) {
            abort(403, 'You do not have permission to view this document');
        }

        // Get workflows and organize by step order for display
        $workflows = DocumentWorkflow::with(['sender', 'recipient', 'recipientOffice'])
            ->where('document_id', $document->id)
            ->orderBy('step_order')
            ->get();

        $docRoute = [];

        // Group recipients by step order
        foreach ($workflows as $workflow) {
            // Add user recipient if available
            if ($workflow->recipient) {
                $recipientName = trim(($workflow->recipient->first_name ?? '') . ' ' . ($workflow->recipient->last_name ?? ''));
                $docRoute[$workflow->step_order][] = [
                    'name' => $recipientName,
                    'type' => 'user',
                    'workflow' => $workflow
                ];
            }
            
            // Add office recipient if available
            if ($workflow->recipient_office && isset($workflow->recipientOffice->name)) {
                $docRoute[$workflow->step_order][] = [
                    'name' => $workflow->recipientOffice->name,
                    'type' => 'office',
                    'workflow' => $workflow
                ];
            }
            
            // If neither recipient nor office, add placeholder
            if ((!$workflow->recipient && !$workflow->recipient_office) || 
                ($workflow->recipient_office && !isset($workflow->recipientOffice->name))) {
                $docRoute[$workflow->step_order][] = [
                    'name' => 'Unassigned',
                    'type' => 'none',
                    'workflow' => $workflow
                ];
            }
        }

        // Check if we should also fetch recipients from the document_recipients table
        if (empty($docRoute)) {
            $documentRecipients = $document->recipients()->with('offices')->get();
            
            if ($documentRecipients->isNotEmpty()) {
                foreach ($documentRecipients as $index => $recipient) {
                    $recipientName = trim(($recipient->first_name ?? '') . ' ' . ($recipient->last_name ?? ''));
                    $docRoute[1][] = [
                        'name' => $recipientName,
                        'type' => 'user',
                        'workflow' => null
                    ];
                }
            }
        }

        $attachments = Document::with('attachments')->findOrFail($document->id)->attachments;
        $auditLogs = DocumentAudit::where('document_id', $document->id)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('documents.show', compact('document', 'auditLogs', 'attachments', 'docRoute', 'workflows'));
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(Document $document): View
    {
        // Load attachments relationship
        $document->load('attachments');

        // Retrieve necessary data for the view
        $userOffice = auth()->user()->offices->pluck('name', 'id');
        $offices = Office::all();
        $categories = DocumentCategory::all()->pluck('category', 'id');

        // Pass only the necessary variables to the view
        return view('documents.edit', compact('document', 'categories', 'offices', 'userOffice'));
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
            'attachments.*' => 'file|mimes:jpeg,png,jpg,gif,pdf,docx|max:10240',
        ]);

        try {
            // Update document basic information
            $document->update([
                'title' => $request->title,
                'description' => $request->description,
                'remarks' => $request->remarks ?? null,
            ]);

            // Handle file upload if new file is provided
            if ($request->hasFile('upload')) {
                Storage::disk('public')->delete($document->path);
                $file = $request->file('upload');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('documents', $fileName, 'public');
                $document->update(['path' => $filePath]);
            }

            // Update document categories
            $document->categories()->sync([$request->classification]);

            // Update document transaction
            $document->transaction()->update([
                'from_office' => $request->from_office,
                'to_office' => $request->to_office,
            ]);

            // Handle new attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $attachment) {
                    $attachmentName = time() . '_' . $attachment->getClientOriginalName();
                    $attachmentPath = $attachment->storeAs('attachments', $attachmentName, 'public');

                    DocumentAttachment::create([
                        'document_id' => $document->id,
                        'filename' => $attachmentName,
                        'path' => $attachmentPath,
                    ]);
                }
            }

            // Log document update
            $this->logDocumentAction($document, 'updated');

            return redirect()->route('documents.index')
                ->with('success', 'Document updated successfully');

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
            ->with('success', 'Document deleted successfully');
    }

    public function deleteAttachment($id)
    {
        $attachment = DocumentAttachment::findOrFail($id);
        $document = Document::findOrFail($attachment->document_id);
        $real_status = $document->status()->get();

        // Delete the file from storage
        Storage::disk('public')->delete($attachment->path);

        // Delete the record from the database
        $attachment->delete();

        $document->status()->update(['status' => $real_status[0]->status]);

        return redirect()->back()->with('success', 'Attachment deleted successfully.');
    }

    public function uploadImage(Request $request)
    {
        $data = $request->input('image');

        // Decode the base64 image
        [$type, $data] = explode(';', $data);
        [, $data] = explode(',', $data);
        $data = base64_decode($data);

        // Generate a unique filename
        $filename = 'uploads/' . uniqid() . '.png';

        // Store the image
        Storage::put($filename, $data);

        return Response::json(['success' => 'Image uploaded successfully', 'filename' => $filename, 'path' => asset('storage/' . $filename)]);
    }

    public function downloadFile($id)
    {
        try {
            $document = Document::findOrFail($id) ?? $document = DocumentAttachment::findOrFail($id);
            $filePath = storage_path('app/public/' . $document->path);

            if (!$document || !$document->path || !file_exists($filePath)) {
                return redirect()->back()->with('error', 'File not found or inaccessible.');
            }

            return response()->download($filePath);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error downloading file: ' . $e->getMessage());
        }
    }

    public function generateTrackingNumber($officeId, $documentTypeId, $length = 5)
    {
        // Retrieve the office abbreviation and document type
        $office = Office::findOrFail($officeId);
        $documentType = DocumentCategory::find($documentTypeId) ?? 'GEN';

        $prefix = strtoupper(substr($office->name, 0, 3)) . '-' . strtoupper(substr($documentType->category, 0, 3));

        do {
            // Define the characters to use for the random part
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $charactersLength = strlen($characters);
            $randomString = '';

            // Generate the random part of the tracking number
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            // Combine the prefix with the random string and a timestamp
            $trackingNumber = $prefix . '-' . $randomString . '-' . date('Y');

            // Check if this tracking number already exists
            $exists = DocumentTrackingNumber::where('tracking_number', $trackingNumber)->exists();
        } while ($exists);

        return $trackingNumber;
    }

    public function generateTrackingSlip($docid = 0, $uploaderid = 0, $tracking_number = 5)
    {
        $document = Document::findOrFail($docid);
        $uploader = User::findOrFail($uploaderid);

        $qr = new QRCode;

        $data = $qr->render($tracking_number);

        return $data;
    }

    public function scanQr($image)
    {
        try {
            $qrReader = new \Zxing\QrReader($image);
            $content = $qrReader->text();
            return $content;
        } catch (\Throwable $e) {
            // oopsies!
            return $e->getMessage();
        }
    }

    //auditing
    private function logDocumentAction($document, $action, $status = null, $details = null)
    {
        DocumentAudit::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'status' => $status ?? $document->status->status,
            'details' => $details,
        ]);
    }

    public function audit()
    {
        $auditLogs = DocumentAudit::with(['document', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('documents.audit', compact('auditLogs'));
    }

    public function cancelWorkflow(Document $document)
    {
        // Cancel all pending workflows
        DocumentWorkflow::where('document_id', $document->id)
            ->whereIn('status', ['pending', 'received'])
            ->update(['status' => 'cancelled']);
        
        // Update document status
        $document->status()->update(['status' => 'cancelled']);
        
        // Log action
        DocumentAudit::logDocumentAction(
            $document->id,
            auth()->id(),
            'workflow',
            'cancelled',
            'Document workflow cancelled'
        );
        
        return redirect()->route('documents.index')
            ->with('success', 'Document workflow has been cancelled.');
    }
}