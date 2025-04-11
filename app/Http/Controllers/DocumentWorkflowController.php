<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\DocumentWorkflow;
use App\Models\Document;
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
            'recipient_batch' => 'array',
            'recipient_office_batch' => 'required|array',
            'step_order' => 'required|array',
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

        foreach ($request->recipient_batch as $batchIndex => $recipients) {
            if (empty($recipients)) {
                continue;
            }
            
            // Get the office IDs for this batch
            $officeIds = $request->recipient_office_batch[$batchIndex] ?? [];
            
            // Process each recipient
            foreach ($recipients as $recipientId) {
                // Get the recipient's office ID (using the first selected office if multiple)
                $recipientOfficeId = null;
                if (!empty($officeIds)) {
                    $recipientOfficeId = $officeIds[0]; // Use first office as default
                } else {
                    // Get the user's office ID as a fallback
                    $user = \App\Models\User::find($recipientId);
                    $recipientOfficeId = $user->office_id ?? 1; // Default to office ID 1 if no office is found
                }
                
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
                ]);
            }
        }

        return redirect()->route('documents.index')
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

        return redirect()->back()->with('success', 'Document workflow approved');
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

        return view('documents.review', compact('workflow', 'document'));
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

    public function changeStatus($workflow_id, $action = null)
    {
        $workflow = DocumentWorkflow::where('tracking_number', $workflow_id)
            ->where(function ($query) {
                $query->where('sender_id', auth()->id())
                    ->orWhere('recipient_id', auth()->id());
            })
            ->firstOrFail();

        if ($action === 'received' || $action === 'find') {
            return route('document.show', $workflow->document_id);
        }

        if (!in_array($action, ['received', 'accepted', 'rejected'])) {
            return redirect()->back()->with('Status', 'Invalid Status');
        }

        $workflow->changeStatus($action);

        return redirect()->back()->with('success', 'Workflow status updated successfully');
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
}
