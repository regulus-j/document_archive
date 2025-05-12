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
use Illuminate\Support\Facades\Storage;


class DocumentWorkflowController extends Controller
{
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
            'recipient_batch' => 'required|array',
            'recipient_batch.*' => 'required|string',
            'step_order' => 'required|array',
            'purpose_batch' => 'required|array',
            'purpose_batch.*' => 'required|string|in:appropriate_action,dissemination,for_comment',
            'urgency_batch' => 'nullable|array',
            'urgency_batch.*' => 'nullable|string|in:low,medium,high,critical',
            'due_date_batch' => 'nullable|array',
            'due_date_batch.*' => 'nullable|date|after_or_equal:today',
        ]);

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
        $request->validate([
            'remarks' => 'required|string|max:1000',
        ]);

        $workflow = DocumentWorkflow::findOrFail($id);
        $workflow->reject();
        $workflow->remarks = $request->remarks;
        $workflow->save();

        // Update document status to indicate revision needed
        $document = Document::findOrFail($workflow->document_id);
        $document->status()->update(['status' => 'needs_revision']);

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
        $workflows = DocumentWorkflow::from('document_workflows as dw_outer')
            ->with(['document', 'sender', 'recipient'])
            ->paginate(15);

        return view('documents.workflow', compact('workflows'));
    }

    public function receiveWorkflow($id): RedirectResponse
    {
        $workflow = DocumentWorkflow::findOrFail($id);
        $workflow->receive();
        
        // Log the receipt action
        DocumentAudit::logDocumentAction(
            $workflow->document_id,
            auth()->id(),
            'workflow',
            'received',
            'Document workflow received'
        );
        
        return redirect()->back()->with('success', 'Document received successfully');
    }

    public function reviewDocument($id)
    {
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
        $request->validate([
            'recipients' => 'required|array',
            'recipients.*' => 'exists:users,id',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $workflow = DocumentWorkflow::findOrFail($id);
        $document = Document::findOrFail($workflow->document_id);
        
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
}
