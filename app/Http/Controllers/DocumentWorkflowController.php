<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            'sender_id'   => 'required|exists:users,id',
            'recipient_id'=> 'required|exists:users,id',
            'step_order'  => 'required|integer',
        ]);

        try {
            $workflow = DocumentWorkflow::create($request->only([
                'document_id', 'sender_id', 'recipient_id', 'step_order',
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
            'step_order'      => 'required|array',
            'remarks'         => 'nullable|array',
        ]);

        $document->status()->update(['status' => 'forwarded']);
        DocumentAudit::logDocumentAction($document, 'forwarded', 'forwarded', 'Document forwarded');

        foreach ($request->recipient_batch as $batchIndex => $recipients) {
            if (empty($recipients)) {
                continue;
            }
            foreach ($recipients as $recipientId) {
                DocumentWorkflow::create([
                    'document_id' => $document->id,
                    'sender_id'   => auth()->id(),
                    'recipient_id'=> $recipientId,
                    'step_order'  => $request->step_order[$batchIndex],
                    'remarks'     => $request->remarks[$batchIndex] ?? null,
                    'status'      => 'pending'
                ]);
            }
        }

        return redirect()->route('documents.index')
            ->with('success', 'Document forwarded successfully');
    }

    public function approveWorkflow($id): RedirectResponse
    {
        $workflow = DocumentWorkflow::findOrFail($id);
        $workflow->approve();
        DocumentAudit::logDocumentAction($workflow->document, 'workflow', 'approved', 'Document workflow approved');

        return redirect()->back()->with('success', 'Document workflow approved');
    }

    public function rejectWorkflow($id): RedirectResponse
    {
        $workflow = DocumentWorkflow::findOrFail($id);
        $workflow->reject();
        DocumentAudit::logDocumentAction($workflow->document, 'workflow', 'rejected', 'Document workflow rejected');

        return redirect()->back()->with('success', 'Document workflow rejected');
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

    public function receiveWorkflow($id)
    {
        $workflow = DocumentWorkflow::findOrFail($id);
        $workflow->receive();
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
            'workflow_id'   => 'required|exists:document_workflows,id',
            'remark'        => 'nullable|string|max:1000',
            'attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,docx|max:10240',
            'action'        => 'required|in:approve,reject',
        ]);

        $workflow = DocumentWorkflow::findOrFail($validated['workflow_id']);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $attachmentName = time() . '_' . $attachment->getClientOriginalName();
                $attachmentPath = $attachment->storeAs('attachments', $attachmentName, 'public');
                \App\Models\DocumentAttachment::create([
                    'document_id' => $workflow->document->id,
                    'filename'    => $attachmentName,
                    'path'        => $attachmentPath,
                ]);
            }
        }

        $workflow->remarks = $validated['remark'] ?? '';
        $workflow->save();

        if ($validated['action'] === 'approve') {
            $workflow->approve();
            DocumentAudit::logDocumentAction($workflow->document, 'workflow', 'approved', 'Document workflow approved during review');
        } else {
            $workflow->reject();
        }

        return redirect()->route('documents.show', $workflow->document->id)
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

        if($action === 'received' || 'find')
        {
            return route('document.show', $workflow->doc_id);
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
