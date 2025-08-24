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
use App\Models\DocumentCategories;
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
            $documents = Document::with(['user.offices', 'status', 'transaction.fromOffice', 'transaction.toOffice'])
                ->latest()
                ->paginate(5);
        } else {
            $userOfficeIds = auth()->user()->offices->pluck('id')->toArray();

            $documents = Document::with(['user.offices', 'status', 'transaction.fromOffice', 'transaction.toOffice'])
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
            ->whereHas('status', function ($q) {
                $q->where('status', 'archived');
            });

        // Apply search if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
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
            'archivedDocuments' => $documents,
            'i' => (request()->input('page', 1) - 1) * 5,
            'search' => $search,
        ], compact('auditLogs')));
    }

    //Storing the document
    public function uploadController(Request $request)
    {
        \Log::info('uploadController called');
        \Log::info("SEARCH ME");
        \Log::info($request);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'classification' => 'required|string',
            'purpose' => 'nullable|string',
            'category' => 'nullable|integer',
            'from_office' => 'required|exists:offices,id',
            'main_document' => 'required|file',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,gif,pdf,docx|max:10240',
            'archive' => 'nullable|string',
            'forward' => 'nullable|string',
            'allowed_viewers' => 'array',
            'allowed_viewers.*' => 'integer',
        ]);

        try {
            \Log::info('Starting document upload process');
            $companyId = auth()->user()->companies()->first()->id ?? 'default';
            $companyPath = $companyId;

            $file = $request->file('main_document');  // Changed from 'upload'
            $fileName = time() . '_' . $file->getClientOriginalName();
            \Log::info('Uploading file', ['fileName' => $fileName]);
            $filePath = $file->storeAs($companyPath . '/documents', $fileName, 'public');

            $document = Document::create([
                'title' => $request->title,
                'uploader' => auth()->id(),
                'description' => $request->description,
                'classification' => $request->classification,
                'purpose' => $request->purpose ?? null,
                'category' => $request->category ?? null,
                'path' => $filePath,
            ]);
            \Log::info('Document created', ['document_id' => $document->id]);

            // Attach the document category - This fixes the categories not being assigned
            if ($request->has('category')) {
                $document->categories()->attach([$request->category]);
                \Log::info('Document category assigned', ['document_id' => $document->id, 'category_id' => $request->category]);
            }

            // Save allowed viewers if classification is Private and provided
            if ($request->classification == 'Private' && $request->has('allowed_viewers')) {
                foreach ($request->allowed_viewers as $userId) {
                    $document->allowedViewers()->create(['user_id' => $userId]);
                }
            }

            // Set initial status based on whether document is being forwarded
            $initialStatus = ($request->forward == '1') ? 'pending' : 'uploaded';
            $document->status()->create([
                'status' => $initialStatus,
            ]);
            \Log::info('Initial document status set to ' . $initialStatus, ['document_id' => $document->id]);

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
                        'storage_size' => $attachment->getSize(),
                        'mime_type' => $attachment->getMimeType(),
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
        $users = User::whereHas('companies', function ($query) use ($userCompanyIds) {
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
        $currentUserCompany = auth()->user()->companies()->first();
        $originatingOfficeId = auth()->user()->offices->first()->id ?? null;
        $offices = $currentUserCompany
            ? Office::where('company_id', $currentUserCompany->id)->where('id', '!=', $originatingOfficeId)->get()
            : collect();

        // Fetch users from the same company, excluding the current user
        $users = $currentUserCompany
            ? User::whereHas('companies', function ($query) use ($currentUserCompany) {
                $query->where('company_id', $currentUserCompany->id);
            })->where('id', '!=', auth()->id())->with('offices')->get()
            : collect();

        $categories = DocumentCategories::all();

        return view('documents.create', compact('offices', 'categories', 'users', 'originatingOfficeId', 'currentUserCompany'));
    }

    /**
     * DEFUNCT, 
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

                        'storage_size' => $attachment->getSize(),

                        'mime_type' => $attachment->getMimeType(),

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
        if (
            !auth()->user()->hasRole('super-admin') &&
            !auth()->user()->hasRole('company-admin') &&
            auth()->user()->id != $document->uploader &&
            !DocumentWorkflow::where('document_id', $document->id)
                ->where('recipient_id', auth()->user()->id)
                ->exists()
        ) {
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
                ($workflow->recipient_office && !isset($workflow->recipientOffice->name))
            ) {
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
            'purpose' => 'nullable|string|in:appropriate_action,comment,disseminate_info',
            'category' => 'nullable|integer',
            'from_office' => 'required|exists:offices,id',
            'main_document' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,docx|max:10240',
            'attachments.*' => 'file|mimes:jpeg,png,jpg,gif,pdf,docx|max:10240',
            'archive' => 'nullable|string',
            'forward' => 'nullable|string',
            'allowed_viewers' => 'array',
            'allowed_viewers.*' => 'integer',
        ]);

        try {
            \Log::info('Starting document update process', ['document_id' => $document->id]);
            
            // Update document basic information
            $document->update([
                'title' => $request->title,
                'description' => $request->description,
                'classification' => $request->classification,
                'purpose' => $request->purpose ?? null,
                'category' => $request->category ?? null,
            ]);
            \Log::info('Document basic info updated', ['document_id' => $document->id]);

            $document->status()->update([
                'status' => 'forwarded',
            ]);
            \Log::info('Document status updated to forwarded', ['document_id' => $document->id]);

            // If document had rejected workflows, reset them to pending so receivers can receive again
            $rejectedWorkflows = \App\Models\DocumentWorkflow::where('document_id', $document->id)
                ->where('status', 'rejected')
                ->get();
            
            foreach ($rejectedWorkflows as $rejectedWorkflow) {
                $rejectedWorkflow->status = 'pending';
                $rejectedWorkflow->received_at = null; // Reset received timestamp
                $rejectedWorkflow->save();
                \Log::info('Reset rejected workflow to pending for re-receipt', [
                    'document_id' => $document->id, 
                    'workflow_id' => $rejectedWorkflow->id,
                    'recipient_id' => $rejectedWorkflow->recipient_id
                ]);
                
                // Notify the receiver that the document has been updated and is ready for re-receipt
                \App\Models\Notifications::create([
                    'user_id' => $rejectedWorkflow->recipient_id,
                    'type' => 'document_updated_after_rejection',
                    'data' => json_encode([
                        'document_id' => $document->id,
                        'message' => 'A rejected document has been updated and is ready for re-receipt.',
                        'title' => $document->title,
                        'updater' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                    ]),
                ]);
            }

            // Handle file upload if new file is provided
            if ($request->hasFile('main_document')) {
                Storage::disk('public')->delete($document->path);
                $companyId = auth()->user()->companies()->first()->id ?? 'default';
                $companyPath = $companyId;
                
                $file = $request->file('main_document');
                $fileName = time() . '_' . $file->getClientOriginalName();
                \Log::info('Uploading new file', ['fileName' => $fileName]);
                $filePath = $file->storeAs($companyPath . '/documents', $fileName, 'public');
                
                $document->update(['path' => $filePath]);
                \Log::info('Document file updated', ['document_id' => $document->id]);
            }

            // Update document categories
            if ($request->has('category')) {
                $document->categories()->detach();
                $document->categories()->attach([$request->category]);
                \Log::info('Document category updated', ['document_id' => $document->id, 'category_id' => $request->category]);
            }

            // Update allowed viewers if classification is Private
            if ($request->classification == 'Private') {
                // Remove old viewers
                $document->allowedViewers()->delete();
                
                // Add new viewers if provided
                if ($request->has('allowed_viewers')) {
                    foreach ($request->allowed_viewers as $userId) {
                        $document->allowedViewers()->create(['user_id' => $userId]);
                    }
                    \Log::info('Document allowed viewers updated', ['document_id' => $document->id]);
                }
            }

            // Update document transaction if forwarding is enabled
            if ($request->has('to_office') && $request->forward == '1') {
                $document->transaction()->updateOrCreate(
                    ['doc_id' => $document->id],
                    [
                        'from_office' => $request->from_office,
                        'to_office' => $request->to_office,
                    ]
                );
                \Log::info('Document transaction updated', ['document_id' => $document->id]);
            }

            // Handle new attachments
            if ($request->hasFile('attachments')) {
                \Log::info('Processing new attachments', ['document_id' => $document->id]);
                foreach ($request->file('attachments') as $attachment) {
                    $companyId = auth()->user()->companies()->first()->id ?? 'default';
                    $companyPath = $companyId;
                    $attachmentName = time() . '_' . $attachment->getClientOriginalName();
                    \Log::info('Uploading attachment', ['attachmentName' => $attachmentName]);
                    $attachmentPath = $attachment->storeAs($companyPath . '/attachments', $attachmentName, 'public');

                    DocumentAttachment::create([
                        'document_id' => $document->id,
                        'filename' => $attachmentName,
                        'path' => $attachmentPath,
                        'storage_size' => $attachment->getSize(),
                        'mime_type' => $attachment->getMimeType(),
                    ]);
                    \Log::info('Attachment record created', ['document_id' => $document->id, 'attachmentName' => $attachmentName]);
                }
            }

            // Update document status if archive is checked
            if ($request->archive == '1') {
                $document->status()->update(['status' => 'archived']);
                \Log::info('Document status updated to archived', ['document_id' => $document->id]);
            }

            // Log document update
            $this->logDocumentAction($document, 'updated', $document->status->status, 'Document updated');
            \Log::info('Document update action logged', ['document_id' => $document->id]);

            // Notify all users who have received or forwarded this document (except current user)
            $workflowUsers = 
                \App\Models\DocumentWorkflow::where('document_id', $document->id)
                    ->where(function($q) {
                        $q->whereNotNull('recipient_id')->orWhereNotNull('sender_id');
                    })
                    ->get(['recipient_id', 'sender_id']);
            $userIds = collect();
            foreach ($workflowUsers as $row) {
                if ($row->recipient_id && $row->recipient_id != auth()->id()) {
                    $userIds->push($row->recipient_id);
                }
                if ($row->sender_id && $row->sender_id != auth()->id()) {
                    $userIds->push($row->sender_id);
                }
            }
            $userIds = $userIds->unique();
            foreach ($userIds as $uid) {
                \App\Models\Notifications::create([
                    'user_id' => $uid,
                    'type' => 'document_updated',
                    'data' => json_encode([
                        'document_id' => $document->id,
                        'message' => 'A document you were involved with was updated.',
                        'title' => $document->title,
                        'updater' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                    ]),
                ]);
            }

            // Handle forward if enabled
            if ($request->forward == '1') {
                \Log::info('Redirecting to forward route', ['document_id' => $document->id]);
                return redirect()->route('documents.forward', $document->id)
                    ->with('success', 'Document updated successfully');
            } else {
                \Log::info('Redirecting to index route', ['document_id' => $document->id]);
                return redirect()->route('documents.index')
                    ->with('success', 'Document updated successfully');
            }
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

    public function deleteAttachment(Request $request, $documentId)
    {
        $attachmentId = $request->query('attachment_id');
        $attachment = DocumentAttachment::findOrFail($attachmentId);
        $document = Document::findOrFail($documentId);
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
        $documentType = DocumentCategory::find($documentTypeId);
        
        if ($documentType) {
            $prefix = strtoupper(substr($office->name, 0, 3)) . '-' . strtoupper(substr($documentType->category, 0, 3));
        } else {
            // Use 'GEN' as fallback for document type abbreviation
            $prefix = strtoupper(substr($office->name, 0, 3)) . '-GEN';
        }

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

  /**
 * Display the document receiving index page.
 * Shows documents forwarded to user from any sender (admin or regular user)
 */
public function receiveIndex(): View
{
    $currentUserId = auth()->id();
    $userOfficeIds = auth()->user()->offices->pluck('id')->toArray();
    
    // Build query to get documents that can be received by the current user
    $documentsQuery = Document::with([
        'user', 
        'status', 
        'workflow', 
        'transaction.fromOffice', 
        'transaction.toOffice',
        'documentWorkflow.sender',
        'documentWorkflow.recipient'
    ]);
    
    // Primary condition: Documents forwarded to this user (from any user)
    $documentsQuery->where(function($query) use ($currentUserId, $userOfficeIds) {
        // 1. Documents with workflows where current user is the recipient
        $query->whereHas('documentWorkflow', function($workflowQuery) use ($currentUserId) {
            $workflowQuery->where('recipient_id', $currentUserId)
                         ->whereIn('status', ['pending', 'received']);
            // FIXED: Removed company-admin restriction to allow user-to-user forwarding
        })
        
        // 2. OR documents sent to user's office (fallback for office-based routing)
        ->orWhere(function($officeQuery) use ($userOfficeIds) {
            $officeQuery->whereHas('transaction', function($transQuery) use ($userOfficeIds) {
                $transQuery->whereIn('to_office', $userOfficeIds);
            });
            // FIXED: Removed company-admin restriction to allow office-based routing from any user
        });
    });
    
    // Exclude completed documents
    $documentsQuery->whereHas('status', function($query) {
        $query->where('status', '!=', 'complete');
    });
    
    // Order by latest first
    $documents = $documentsQuery->latest()->paginate(10);
        
    return view('documents.receive', compact('documents'));
}

/**
 * Confirm receipt of a document.
 * Reformed to handle admin-sent documents properly
 */
public function receiveConfirm(Document $document)
{
    try {
        $currentUserId = auth()->id();
        
        // Find the specific workflow for this user (if exists)
        $userWorkflow = DocumentWorkflow::where('document_id', $document->id)
            ->where('recipient_id', $currentUserId)
            ->whereIn('status', ['pending', 'received'])
            ->first();
            
        if ($userWorkflow) {
            // Update the specific workflow status to received
            $userWorkflow->receive(); // This will now also sync document status
            
            // Log the action
            DocumentAudit::logDocumentAction(
                $document->id,
                $currentUserId,
                'workflow',
                'received',
                'Document received via workflow by ' . auth()->user()->first_name . ' ' . auth()->user()->last_name
            );
        } else {
            // If no specific workflow found, check if document has any workflow and update main document status
            $hasWorkflow = DocumentWorkflow::where('document_id', $document->id)->exists();
            
            if (!$hasWorkflow) {
                // Create a workflow entry for this receipt
                $workflow = DocumentWorkflow::create([
                    'document_id' => $document->id,
                    'sender_id' => $document->uploader,
                    'recipient_id' => $currentUserId,
                    'step_order' => 1,
                    'status' => 'received',
                    'received_at' => now(),
                    'tracking_number' => 'RCV-' . time() . '-' . $document->id,
                ]);
                
                // This will automatically sync document status
                $workflow->receive();
            } else {
                // Update document status directly if workflow exists but user not in it
                $document->status()->update(['status' => 'received']);
            }
            
            // Log the action
            DocumentAudit::logDocumentAction(
                $document->id,
                $currentUserId,
                'document',
                'received',
                'Document received directly by ' . auth()->user()->first_name . ' ' . auth()->user()->last_name
            );
        }
        
        return redirect()->route('documents.receive.index')
            ->with('success', 'Document has been successfully received.');
            
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Failed to receive document: ' . $e->getMessage());
    }
}

/**
     * Recall a document, pause workflow, and notify recipients.
     */
    public function recallDocument(Request $request, Document $document)
    {
        // Only the document owner can recall
        if ($document->uploader !== auth()->id()) {
            return redirect()->back()->with('error', 'You are not authorized to recall this document.');
        }

        // Pause workflow: set a status or flag (e.g., 'recalled')
        $document->status()->update(['status' => 'recalled']);

        // Pause all associated workflows
        $workflows = \App\Models\DocumentWorkflow::where('document_id', $document->id)->get();
        foreach ($workflows as $workflow) {
            $workflow->pause();
        }

        // Notify all recipients in the workflow
        foreach ($workflows as $workflow) {
            if ($workflow->recipient_id) {
                \App\Models\Notifications::create([
                    'user_id' => $workflow->recipient_id,
                    'type' => 'document_recall',
                    'data' => json_encode([
                        'message' => 'A document you are a recipient of has been recalled and the workflow is paused.',
                        'title' => $document->title,
                        'document_id' => $document->id,
                        'recalled_by' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                    ]),
                ]);
            }
        }

        // Log the recall action
        \App\Models\DocumentAudit::logDocumentAction(
            $document->id, 
            auth()->id(),
            'recall', 
            'recalled', 
            'Document recalled and workflow paused'
        );

        return redirect()->back()->with('success', 'Document has been recalled and workflow paused. Recipients have been notified.');
    }

    /**
     * Resume a recalled document and its workflow.
     */
    public function resumeDocument(Request $request, Document $document)
    {
        // Only the document owner can resume
        if ($document->uploader !== auth()->id()) {
            return redirect()->back()->with('error', 'You are not authorized to resume this document.');
        }

        // Check if the document was recalled
        if ($document->status->status !== 'recalled') {
            return redirect()->back()->with('error', 'This document is not in a recalled state.');
        }

        // Set document status back to active
        $document->status()->update(['status' => 'forwarded']);

        // Resume all associated workflows
        $workflows = \App\Models\DocumentWorkflow::where('document_id', $document->id)->get();
        foreach ($workflows as $workflow) {
            $workflow->resume();
        }

        // Notify all recipients in the workflow
        foreach ($workflows as $workflow) {
            if ($workflow->recipient_id) {
                \App\Models\Notifications::create([
                    'user_id' => $workflow->recipient_id,
                    'type' => 'document_resumed',
                    'data' => json_encode([
                        'message' => 'A document that was previously recalled has been resumed. The workflow is now active again.',
                        'title' => $document->title,
                        'document_id' => $document->id,
                        'resumed_by' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                    ]),
                ]);
            }
        }

        // Log the resume action
        \App\Models\DocumentAudit::logDocumentAction(
            $document->id,
            auth()->id(),
            'resume', 
            'forwarded', 
            'Document workflow resumed'
        );

        return redirect()->back()->with('success', 'Document workflow has been resumed. Recipients have been notified.');
    }

    /**
     * Archive a document
     * 
     * @param Document $document
     * @return \Illuminate\Http\RedirectResponse
     */
    public function archiveDocument(Document $document)
    {
        // Check if the user has permission to archive the document
        if (auth()->user()->id !== $document->user_id && !auth()->user()->hasRole('company-admin')) {
            return redirect()->route('documents.index')
                ->with('error', 'You do not have permission to archive this document.');
        }

        // Update the document status to archived
        $document->status()->update(['status' => 'archived']);

        // Create an audit log
        DocumentAudit::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'action' => 'Archived',
            'status' => 'Archived',
            'details' => 'Document was archived'
        ]);

        // Log the action
        \Log::info('Document archived', ['document_id' => $document->id, 'user_id' => auth()->id()]);

        return redirect()->route('documents.index')
            ->with('success', 'Document has been archived successfully.');
    }
}
