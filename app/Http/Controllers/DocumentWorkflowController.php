<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\DocumentWorkflow;
use App\Models\Document;
use App\Models\User;
use App\Models\CompanyUser;
use App\Models\DocumentAttachment;
use App\Models\DocumentAudit;
use App\Models\WorkflowTemplate;
use App\Services\SequentialWorkflowService;
use Illuminate\Support\Facades\Storage;


class DocumentWorkflowController extends Controller
{
    /**
     * Check if user can access workflow for a document
     * User must have "received" the document first in the receive view
     */
    private function canAccessWorkflow($workflowId, $userId = null)
    {
        $userId = $userId ?: auth()->id();
        
        $workflow = DocumentWorkflow::find($workflowId);
        if (!$workflow) {
            return false;
        }
        
        // Check if document has been recalled
        if ($workflow->document && $workflow->document->status && $workflow->document->status->status === 'recalled') {
            return false;
        }
        
        // If user is the sender, they can always access (to monitor)
        if ($workflow->sender_id === $userId) {
            return true;
        }
        
        // If user is recipient, they must have "received" the document first
        if ($workflow->recipient_id === $userId) {
            return in_array($workflow->status, ['received', 'approved', 'rejected', 'returned', 'referred', 'forwarded']);
        }
        
        return false;
    }
    
    /**
     * Middleware-like check for workflow access
     */
    private function ensureWorkflowAccess($workflowId)
    {
        $workflow = DocumentWorkflow::find($workflowId);
        
        // Check if document has been recalled
        if ($workflow && $workflow->document && $workflow->document->status && $workflow->document->status->status === 'recalled') {
            return redirect()->route('documents.workflows')
                ->with('error', 'This document has been recalled by the sender. Workflow actions are no longer available.');
        }
        
        if (!$this->canAccessWorkflow($workflowId)) {
            return redirect()->route('documents.receive.index')
                ->with('error', 'You must receive this document first before accessing the workflow. Please check the "Receive Documents" section.');
        }
        
        return null;
    }
    // workflow logic
    public function createWorkflow(Request $request): RedirectResponse
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'sender_id' => 'required|exists:users,id',
            'recipient_id' => 'required|exists:users,id',
            'step_order' => 'required|integer',
        ]);

        try {
            $workflow = DocumentWorkflow::create($request->only([
                'document_id',
                'sender_id',
                'recipient_id',
                'step_order',
            ]));

            DocumentAudit::logDocumentAction($workflow->document, 'workflow', 'pending', 'Document workflow created');

            return redirect()->back()->with('success', 'Document workflow created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating document workflow: ' . $e->getMessage());
        }
    }

    public function forwardDocumentSubmit(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $request->validate([
            'recipient_batch' => 'required_without:use_template|array',
            'recipient_batch.*' => 'required_without:use_template|string',
            'step_order' => 'required_without:use_template|array',
            'purpose_batch' => 'required_without:use_template|array',
            'purpose_batch.*' => 'required_without:use_template|string|in:appropriate_action,dissemination,for_comment',
            'urgency_batch' => 'nullable|array',
            'urgency_batch.*' => 'nullable|string|in:low,medium,high,critical',
            'due_date_batch' => 'nullable|array',
            'due_date_batch.*' => 'nullable|date|after_or_equal:today',
            'use_template' => 'nullable|boolean',
            'template_id' => 'nullable|exists:workflow_templates,id',
        ]);

        // Check if user wants to apply a workflow template
        if ($request->use_template && $request->template_id) {
            return $this->forwardDocumentWithTemplate($request, $document);
        }

        // Original forwarding logic (without template)
        $document->status()->update(['status' => 'forwarded']);

        // Generate tracking number
        $trackingNumber = $this->createTrackingNumber($document, auth()->user());

        // Log the forward action
        DocumentAudit::logDocumentAction(
            $document->id,
            auth()->id(),
            'forward',
            'forwarded',
            'Document forwarded'
        );

        $recipientBatches = $request->recipient_batch ?? [];
        
        // Keep track of all recipient IDs to sync with document_recipients table
        $allRecipientIds = [];
        
        foreach ($recipientBatches as $batchIndex => $recipientValue) {
            if (empty($recipientValue)) {
                continue;
            }
            
            // Parse the recipient value to determine if it's an office or user
            // Format: "office_ID" or "user_ID"
            $parts = explode('_', $recipientValue);
            $type = $parts[0];
            $id = intval($parts[1]);
            
            if ($type === 'user') {
                // It's a user recipient
                $recipientId = $id;
                $allRecipientIds[] = $recipientId;
                
                // Get the user's office ID as a fallback
                $user = \App\Models\User::find($recipientId);
                $recipientOfficeId = $user->office_id ?? 1; // Default to office ID 1 if no office is found
                
                DocumentWorkflow::create([
                    'tracking_number' => $trackingNumber,
                    'document_id' => $document->id,
                    'sender_id' => auth()->id(),
                    'recipient_id' => $recipientId,
                    'recipient_office' => $recipientOfficeId,
                    'step_order' => $request->step_order[$batchIndex],
                    'remarks' => $request->remarks[$batchIndex] ?? null,
                    'status' => 'pending',
                    'received_at' => null,
                    'purpose' => $request->purpose_batch[$batchIndex] ?? null,
                    'urgency' => $request->urgency_batch[$batchIndex] ?? null,
                    'due_date' => $request->due_date_batch[$batchIndex] ?? null,
                ]);

                // Notify the user recipient
                \App\Models\Notifications::create([
                    'user_id' => $recipientId,
                    'type' => 'document_forwarded',
                    'data' => json_encode([
                        'document_id' => $document->id,
                        'message' => 'A document has been forwarded to you.',
                        'title' => $document->title,
                        'sender' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                    ]),
                ]);
            } else if ($type === 'office') {
                // It's an office recipient
                $officeId = $id;
                
                DocumentWorkflow::create([
                    'tracking_number' => $trackingNumber,
                    'document_id' => $document->id,
                    'sender_id' => auth()->id(),
                    'recipient_id' => null, // No specific recipient for office
                    'recipient_office' => $officeId,
                    'step_order' => $request->step_order[$batchIndex],
                    'remarks' => $request->remarks[$batchIndex] ?? null,
                    'status' => 'pending',
                    'received_at' => null,
                    'purpose' => $request->purpose_batch[$batchIndex] ?? null,
                    'urgency' => $request->urgency_batch[$batchIndex] ?? null,
                    'due_date' => $request->due_date_batch[$batchIndex] ?? null,
                ]);
            }
        }
        
        // Sync all recipient IDs with the document_recipients table to ensure proper recipient data
        if (!empty($allRecipientIds)) {
            foreach ($allRecipientIds as $recipientId) {
                \DB::table('document_recipients')->updateOrInsert(
                    ['document_id' => $document->id, 'recipient_id' => $recipientId],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }

        $docQR = $document->trackingNumber();
        $qrCodeData = app(DocumentController::class)->generateTrackingSlip($document->id, auth()->id(), $trackingNumber);
        
        return redirect()->route('documents.index')
        ->with('data', $qrCodeData)
        ->with('success', 'Document forwarded successfully');
    }

    public function approveWorkflow(Request $request, $id): RedirectResponse
    {
        // Check if user can access this workflow
        $accessCheck = $this->ensureWorkflowAccess($id);
        if ($accessCheck) return $accessCheck;
        
        $workflow = DocumentWorkflow::findOrFail($id);
        $workflow->approve();
        
        // Save remarks if provided
        if ($request->has('remarks') && !empty($request->remarks)) {
            $workflow->remarks = $request->remarks;
            $workflow->save();
        }

        // Log with remarks if provided
        $logMessage = 'Document workflow approved';
        if ($request->has('remarks') && !empty($request->remarks)) {
            $logMessage .= ': ' . $request->remarks;
        }
        
        DocumentAudit::logDocumentAction(
            $workflow->document_id,
            auth()->id(),
            'workflow',
            'approved',
            $logMessage
        );

        // Notify next recipient (if any)
        $nextWorkflow = \App\Models\DocumentWorkflow::where('document_id', $workflow->document_id)
            ->where('step_order', '>', $workflow->step_order)
            ->orderBy('step_order')
            ->first();
        if ($nextWorkflow && $nextWorkflow->recipient_id && $nextWorkflow->recipient_id != auth()->id()) {
            \App\Models\Notifications::create([
                'user_id' => $nextWorkflow->recipient_id,
                'type' => 'document_next_step',
                'data' => json_encode([
                    'document_id' => $workflow->document_id,
                    'message' => 'A document is now assigned to you in the workflow.',
                    'title' => $workflow->document->title,
                    'from' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                ]),
            ]);
        }
        // Notify previous sender (if any)
        if ($workflow->sender_id && $workflow->sender_id != auth()->id()) {
            \App\Models\Notifications::create([
                'user_id' => $workflow->sender_id,
                'type' => 'document_next_step',
                'data' => json_encode([
                    'document_id' => $workflow->document_id,
                    'message' => 'A document you forwarded has moved to the next step.',
                    'title' => $workflow->document->title,
                    'to' => $nextWorkflow && $nextWorkflow->recipient_id ? $nextWorkflow->recipient->first_name . ' ' . $nextWorkflow->recipient->last_name : null,
                ]),
            ]);
        }

        return redirect()->route('documents.index')
            ->with('success', 'Document workflow approved');
    }

    public function rejectWorkflow(Request $request, $id): RedirectResponse
    {
        // Check if user can access this workflow
        $accessCheck = $this->ensureWorkflowAccess($id);
        if ($accessCheck) return $accessCheck;
        
        $request->validate([
            'remarks' => 'required|string|max:1000',
        ]);

        $workflow = DocumentWorkflow::findOrFail($id);
        $workflow->reject();
        $workflow->remarks = $request->remarks;
        $workflow->save();

        // Log with remarks
        DocumentAudit::logDocumentAction(
            $workflow->document_id,
            auth()->id(),
            'workflow',
            'rejected',
            'Document rejected: ' . $request->remarks
        );

        // Optional: Notify the sender
        if (class_exists('\App\Notifications\DocumentRejected')) {
            $document->sender->notify(new \App\Notifications\DocumentRejected($document, $workflow));
        }

        return redirect()->route('documents.index')
            ->with('success', 'Document rejected. The sender has been notified to revise or cancel the workflow.');
    }

    // helper functions
    private function storeFile($file, $company_id)
    {
        Storage::disk('local')->put(date('mYd'), 'Contents');
    }

    // workflow management
    public function workflowManagement()
    {
        $currentUserId = auth()->id();
        
        // Only show workflows where the user has already "received" the document
        // This enforces the receive-first, then workflow logic
        // Also exclude recalled documents
        $workflows = DocumentWorkflow::with(['document.status', 'sender', 'recipient'])
            ->where(function($query) use ($currentUserId) {
                $query->where('recipient_id', $currentUserId)
                      ->where('status', '!=', 'pending'); // Must have moved past pending (i.e., received)
            })
            ->orWhere(function($query) use ($currentUserId) {
                // Also show workflows where user is the sender (they can monitor progress)
                $query->where('sender_id', $currentUserId);
            })
            ->whereHas('document', function($query) {
                $query->whereHas('status', function($statusQuery) {
                    $statusQuery->where('status', '!=', 'recalled');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get pending documents that need to be received first (exclude recalled)
        $pendingReceive = DocumentWorkflow::with(['document.status', 'sender'])
            ->where('recipient_id', $currentUserId)
            ->where('status', 'pending')
            ->whereHas('document', function($query) {
                $query->whereHas('status', function($statusQuery) {
                    $statusQuery->where('status', '!=', 'recalled');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('documents.workflow', compact('workflows', 'pendingReceive'));
    }

    public function receiveWorkflow($id): RedirectResponse
    {
        // This method is now deprecated - users should use the receive documents feature first
        return redirect()->route('documents.receive.index')
            ->with('info', 'Please use the "Receive Documents" feature to receive documents first, then access them in the workflow.');
    }

    public function reviewDocument($id)
    {
        // Check if user can access this workflow
        $accessCheck = $this->ensureWorkflowAccess($id);
        if ($accessCheck) return $accessCheck;
        
        $workflow = DocumentWorkflow::findOrFail($id);
        $document = $workflow->document;
        
        // Get company ID from document or authenticated user's first company
        $companyId = $document->company_id ?? null;
        
        // If document doesn't have company_id, try to get it from the authenticated user
        if (!$companyId) {
            $userCompany = CompanyUser::where('user_id', auth()->id())->first();
            $companyId = $userCompany ? $userCompany->company_id : null;
        }
        
        // Default to empty collection if no company found
        $companyUsers = collect();
        
        if ($companyId) {
            // Get users from the same company via the pivot table
            $companyUserIds = CompanyUser::where('company_id', $companyId)
                ->where('user_id', '!=', auth()->id())
                ->pluck('user_id');
                
            // Get the actual user objects
            $companyUsers = User::whereIn('id', $companyUserIds)->get();
        }

        return view('documents.review', compact('workflow', 'document', 'companyUsers'));
    }

    public function reviewSubmit(Request $request)
    {
        $validated = $request->validate([
            'workflow_id' => 'required|exists:document_workflows,id',
            'remark' => 'nullable|string|max:1000',
            'attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,docx|max:10240',
            'action' => 'required|in:approve,reject',
        ]);

        $workflow = DocumentWorkflow::findOrFail($validated['workflow_id']);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $attachmentName = time() . '_' . $attachment->getClientOriginalName();
                $attachmentPath = $attachment->storeAs('attachments', $attachmentName, 'public');
                DocumentAttachment::create([
                    'document_id' => $workflow->document->id,
                    'filename' => $attachmentName,
                    'path' => $attachmentPath,
                    'storage_size' => $attachment->getSize(),
                    'mime_type' => $attachment->getMimeType(),
                ]);
            }
        }

        $workflow->remarks = $validated['remark'] ?? '';
        $workflow->save();

        if ($validated['action'] === 'approve') {
            $workflow->approve();
            // Fix: Pass document ID instead of document object
            DocumentAudit::logDocumentAction(
                $workflow->document_id,
                auth()->id(),
                'review',
                'approved',
                'Document workflow approved during review' . ($validated['remark'] ? ": {$validated['remark']}" : '')
            );
        } else {
            $workflow->reject();
            // Fix: Pass document ID instead of document object
            DocumentAudit::logDocumentAction(
                $workflow->document_id,
                auth()->id(),
                'review',
                'rejected',
                'Document workflow rejected during review' . ($validated['remark'] ? ": {$validated['remark']}" : '')
            );
        }

        return redirect()->route('documents.show', $workflow->document_id)
            ->with('success', 'Review submitted successfully');
    }

    public function returnWorkflow(Request $request, $id): RedirectResponse
    {
        // Check if user can access this workflow
        $accessCheck = $this->ensureWorkflowAccess($id);
        if ($accessCheck) return $accessCheck;
        
        $request->validate([
            'remarks' => 'required|string|max:1000',
        ]);

        $workflow = DocumentWorkflow::findOrFail($id);
        $workflow->return();
        $workflow->remarks = $request->remarks;
        $workflow->save();

        // Update document status to indicate it's returned to uploader
        $document = Document::findOrFail($workflow->document_id);
        $document->status()->update(['status' => 'returned']);

        // Log with remarks
        DocumentAudit::logDocumentAction(
            $workflow->document_id,
            auth()->id(),
            'workflow',
            'returned',
            'Document returned to uploader: ' . $request->remarks
        );

        return redirect()->route('documents.index')
            ->with('success', 'Document returned to uploader. The uploader will need to revise the document based on your remarks.');
    }

    public function referWorkflow(Request $request, $id): RedirectResponse
    {
        // Check if user can access this workflow
        $accessCheck = $this->ensureWorkflowAccess($id);
        if ($accessCheck) return $accessCheck;
        
        $request->validate([
            'recipients' => 'required|array',
            'recipients.*' => 'exists:users,id',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $workflow = DocumentWorkflow::findOrFail($id);
        $document = Document::findOrFail($workflow->document_id);
        
        // Mark the current workflow as referred
        $workflow->refer();
        $workflow->remarks = $request->remarks ?? '';
        $workflow->save();
        
        // Generate tracking number for new workflow stages
        $trackingNumber = $this->createTrackingNumber($document, auth()->user());
        
        // Find the last step order used in existing workflow
        $lastStepOrder = DocumentWorkflow::where('document_id', $document->id)
            ->max('step_order');
            
        $newStepOrder = $lastStepOrder + 1;
        
        // Create new workflow entries for each additional recipient
        foreach ($request->recipients as $recipientId) {
            // Skip if trying to refer to self
            if ($recipientId == auth()->id()) {
                continue;
            }
            
            // Get the user's office ID
            $user = \App\Models\User::find($recipientId);
            $recipientOfficeId = $user->office_id ?? null;
            
            DocumentWorkflow::create([
                'tracking_number' => $trackingNumber,
                'document_id' => $document->id,
                'sender_id' => auth()->id(),
                'recipient_id' => $recipientId,
                'recipient_office' => $recipientOfficeId,
                'step_order' => $newStepOrder,
                'remarks' => $request->remarks ?? null,
                'status' => 'pending',
                'received_at' => null,
            ]);

            // Notify the referred user
            \App\Models\Notifications::create([
                'user_id' => $recipientId,
                'type' => 'document_referred',
                'data' => json_encode([
                    'document_id' => $document->id,
                    'message' => 'A document has been referred to you.',
                    'title' => $document->title,
                    'sender' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                ]),
            ]);
        }
        
        // Log action
        DocumentAudit::logDocumentAction(
            $workflow->document_id,
            auth()->id(),
            'workflow',
            'referred',
            'Document referred to additional recipients: ' . ($request->remarks ? $request->remarks : 'No remarks')
        );

        return redirect()->route('documents.index')
            ->with('success', 'Document referred to additional recipients successfully.');
    }

    public function forwardFromWorkflow(Request $request, $id): RedirectResponse
    {
        // Check if user can access this workflow
        $accessCheck = $this->ensureWorkflowAccess($id);
        if ($accessCheck) return $accessCheck;
        
        $request->validate([
            'recipients' => 'required|array',
            'recipients.*' => 'exists:users,id',
            'remarks' => 'nullable|string|max:1000',
            'use_template' => 'nullable|boolean',
            'template_id' => 'nullable|exists:workflow_templates,id',
        ]);

        $workflow = DocumentWorkflow::findOrFail($id);
        $document = Document::findOrFail($workflow->document_id);
        
        // Check if user wants to apply a workflow template
        if ($request->use_template && $request->template_id) {
            return $this->forwardWithTemplate($request, $workflow, $document);
        }
        
        // Original forward logic (without template)
        // Keep the current workflow unchanged but mark as forwarded
        $workflow->forward();
        $workflow->remarks = $request->remarks ?? '';
        $workflow->save();
        
        // Generate tracking number for new workflow stages
        $trackingNumber = $this->createTrackingNumber($document, auth()->user());
        
        // Get the last step order for the document workflow
        $lastStepOrder = DocumentWorkflow::where('document_id', $document->id)
            ->max('step_order');
            
        $newStepOrder = $lastStepOrder + 1;
        
        // Create new workflow entries for each recipient
        foreach ($request->recipients as $recipientId) {
            // Skip if trying to forward to self
            if ($recipientId == auth()->id()) {
                continue;
            }
            
            // Get the user's office ID
            $user = \App\Models\User::find($recipientId);
            $recipientOfficeId = $user->office_id ?? null;
            
            DocumentWorkflow::create([
                'tracking_number' => $trackingNumber,
                'document_id' => $document->id,
                'sender_id' => auth()->id(),
                'recipient_id' => $recipientId,
                'recipient_office' => $recipientOfficeId,
                'step_order' => $newStepOrder,
                'remarks' => $request->remarks ?? null,
                'status' => 'pending',
                'received_at' => null,
            ]);

            // Notify the forwarded user
            \App\Models\Notifications::create([
                'user_id' => $recipientId,
                'type' => 'document_forwarded',
                'data' => json_encode([
                    'document_id' => $document->id,
                    'message' => 'A document has been forwarded to you.',
                    'title' => $document->title,
                    'sender' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                ]),
            ]);
        }
        
        // Log action
        DocumentAudit::logDocumentAction(
            $workflow->document_id,
            auth()->id(),
            'workflow',
            'forwarded',
            'Document forwarded from review: ' . ($request->remarks ? $request->remarks : 'No remarks')
        );

        return redirect()->route('documents.index')
            ->with('success', 'Document forwarded to new recipients successfully.');
    }

    private function createTrackingNumber(Document $document, $user)
    {
        $originatingOffice = $user->originating_office ?? 'UNK';
        $officeCode = strtoupper(substr($originatingOffice, 0, 3));
        $uploadDate = $document->created_at
            ? $document->created_at->format('Ymd')
            : date('Ymd');
        $numberPart = str_pad($document->id, 6, '0', STR_PAD_LEFT);

        return "{$officeCode}-{$uploadDate}-{$numberPart}";
    }

    // Method to handle receipt confirmation
    public function confirmReceipt(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
        ]);

        $document = Document::findOrFail($request->input('document_id'));

        // Update the workflow status to 'received'
        $workflow = $document->workflow;
        if ($workflow) {
            $workflow->status = 'received';
            $workflow->save();
        }

        return redirect()->route('documents.receive.index')->with('success', 'Document status updated to received.');
    }

    /**
     * Add comment to workflow (for 'for_comment' purpose)
     */
    public function addComment(Request $request, $id): RedirectResponse
    {
        // Check if user can access this workflow
        $accessCheck = $this->ensureWorkflowAccess($id);
        if ($accessCheck) return $accessCheck;
        
        $request->validate([
            'remarks' => 'required|string|max:1000',
        ]);
        
        $workflow = DocumentWorkflow::findOrFail($id);
        
        // Ensure this is a comment purpose workflow
        if ($workflow->purpose !== 'for_comment') {
            return redirect()->back()->with('error', 'This action is only available for documents requesting comments.');
        }
        
        // Update workflow with comment
        $workflow->status = 'commented';
        $workflow->remarks = $request->remarks;
        $workflow->received_at = now();
        $workflow->save();
        
        // Update document status directly
        if ($workflow->document && $workflow->document->status) {
            $workflow->document->status()->update(['status' => 'commented']);
        }
        
        // Log the action
        DocumentAudit::logDocumentAction(
            $workflow->document_id,
            auth()->id(),
            'workflow',
            'commented',
            'Comment added: ' . $request->remarks
        );

        // Notify sender about the comment
        if ($workflow->sender_id && $workflow->sender_id != auth()->id()) {
            \App\Models\Notifications::create([
                'user_id' => $workflow->sender_id,
                'title' => 'Document Comment Received',
                'message' => 'A comment has been added to document: ' . $workflow->document->title,
                'type' => 'workflow_comment',
                'data' => json_encode([
                    'document_id' => $workflow->document_id,
                    'workflow_id' => $workflow->id,
                    'commenter' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                    'comment' => $request->remarks
                ])
            ]);
        }

        return redirect()->route('documents.workflows')->with('success', 'Comment submitted successfully.');
    }

    /**
     * Acknowledge workflow (for 'dissemination' purpose)
     */
    public function acknowledgeWorkflow(Request $request, $id): RedirectResponse
    {
        // Check if user can access this workflow
        $accessCheck = $this->ensureWorkflowAccess($id);
        if ($accessCheck) return $accessCheck;
        
        $request->validate([
            'remarks' => 'nullable|string|max:1000',
        ]);
        
        $workflow = DocumentWorkflow::findOrFail($id);
        
        // Ensure this is a dissemination purpose workflow
        if ($workflow->purpose !== 'dissemination') {
            return redirect()->back()->with('error', 'This action is only available for information dissemination documents.');
        }
        
        // Update workflow with acknowledgment
        $workflow->status = 'acknowledged';
        if ($request->remarks) {
            $workflow->remarks = $request->remarks;
        }
        $workflow->received_at = now();
        $workflow->save();
        
        // Update document status directly
        if ($workflow->document && $workflow->document->status) {
            $workflow->document->status()->update(['status' => 'acknowledged']);
        }
        
        // Log the action
        $logMessage = 'Document information acknowledged';
        if ($request->remarks) {
            $logMessage .= ': ' . $request->remarks;
        }
        
        DocumentAudit::logDocumentAction(
            $workflow->document_id,
            auth()->id(),
            'workflow',
            'acknowledged',
            $logMessage
        );

        // Notify sender about the acknowledgment
        if ($workflow->sender_id && $workflow->sender_id != auth()->id()) {
            \App\Models\Notifications::create([
                'user_id' => $workflow->sender_id,
                'title' => 'Document Information Acknowledged',
                'message' => 'Document information has been acknowledged: ' . $workflow->document->title,
                'type' => 'workflow_acknowledged',
                'data' => json_encode([
                    'document_id' => $workflow->document_id,
                    'workflow_id' => $workflow->id,
                    'acknowledger' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                    'remarks' => $request->remarks
                ])
            ]);
        }

        return redirect()->route('documents.workflows')->with('success', 'Document information acknowledged successfully.');
    }

    /**
     * Forward document using a workflow template (initial forwarding)
     */
    private function forwardDocumentWithTemplate(Request $request, Document $document)
    {
        try {
            $template = WorkflowTemplate::findOrFail($request->template_id);
            
            // Extract recipients from the form data
            $recipients = [];
            $recipientBatches = $request->recipient_batch ?? [];
            
            foreach ($recipientBatches as $batchIndex => $recipientValue) {
                if (empty($recipientValue)) {
                    continue;
                }
                
                // Parse the recipient value to determine if it's an office or user
                $parts = explode('_', $recipientValue);
                $type = $parts[0];
                $id = intval($parts[1]);
                
                if ($type === 'user') {
                    $recipients[] = ['user_id' => $id];
                } else if ($type === 'office') {
                    // For office recipients, get all users in that office
                    $officeUsers = \App\Models\User::whereHas('offices', function($query) use ($id) {
                        $query->where('offices.id', $id);
                    })->get();
                    
                    foreach ($officeUsers as $user) {
                        $recipients[] = ['user_id' => $user->id];
                    }
                }
            }
            
            // Validate that we have recipients
            if (empty($recipients)) {
                throw new \Exception('No recipients selected. Please select at least one recipient or office.');
            }
            
            // Update document status
            $document->status()->update(['status' => 'forwarded']);

            // Log the forward action
            DocumentAudit::logDocumentAction(
                $document->id,
                auth()->id(),
                'forward',
                'forwarded',
                'Document forwarded using template: ' . $template->name
            );

            // Use the sequential workflow service to create template-based workflow
            $sequentialWorkflowService = app(SequentialWorkflowService::class);
            
            $overrides = [
                'description' => 'Document forwarded using template: ' . $template->name,
                'urgency' => $request->urgency_batch[0] ?? 'medium',
                'recipients' => $recipients
            ];

            $workflowChain = $sequentialWorkflowService->createFromTemplate(
                $template,
                $document,
                auth()->user(),
                $overrides
            );

            // Generate QR code data for tracking
            $trackingNumber = $this->createTrackingNumber($document, auth()->user());
            $qrCodeData = app(DocumentController::class)->generateTrackingSlip($document->id, auth()->id(), $trackingNumber);

            return redirect()->route('documents.index')
                ->with('data', $qrCodeData)
                ->with('success', "Document forwarded using template '{$template->name}' to " . count($recipients) . " recipient(s) with {$workflowChain->total_steps} workflow steps created.");

        } catch (\Exception $e) {
            \Log::error('Error forwarding document with template', [
                'error' => $e->getMessage(),
                'template_id' => $request->template_id,
                'document_id' => $document->id
            ]);

            return redirect()->back()
                ->with('error', 'Failed to apply workflow template: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Forward document using a workflow template (from workflow review)
     */
    private function forwardWithTemplate(Request $request, DocumentWorkflow $workflow, Document $document): RedirectResponse
    {
        try {
            $template = WorkflowTemplate::findOrFail($request->template_id);
            
            // Mark current workflow as forwarded
            $workflow->forward();
            $workflow->remarks = $request->remarks ?? 'Forwarded using template: ' . $template->name;
            $workflow->save();

            // Prepare recipients for template application
            $recipients = collect($request->recipients)->map(function($userId) {
                return ['user_id' => $userId];
            })->toArray();

            // Use the sequential workflow service to create template-based workflow
            $sequentialWorkflowService = app(SequentialWorkflowService::class);
            
            $overrides = [
                'description' => $request->remarks,
                'urgency' => 'medium',
                'recipients' => $recipients
            ];

            $workflowChain = $sequentialWorkflowService->createFromTemplate(
                $template,
                $document,
                auth()->user(),
                $overrides
            );

            return redirect()->route('documents.workflows')
                ->with('success', "Document forwarded using template '{$template->name}' with {$workflowChain->total_steps} workflow steps created.");

        } catch (\Exception $e) {
            \Log::error('Error forwarding with template', [
                'error' => $e->getMessage(),
                'template_id' => $request->template_id,
                'document_id' => $document->id
            ]);

            return redirect()->back()
                ->with('error', 'Failed to apply workflow template. Using standard forward instead.')
                ->withInput();
        }
    }
}
