<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentWorkflow;
use App\Models\WorkflowChain;
use App\Models\WorkflowTemplate;
use App\Services\SequentialWorkflowService;
use App\Services\WorkflowTemplateService;
use App\Services\WorkflowNotificationService;
use App\Services\WorkflowProgressService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;

class SequentialWorkflowController extends Controller
{
    protected $workflowService;
    protected $templateService;
    protected $notificationService;
    protected $progressService;

    public function __construct(
        SequentialWorkflowService $workflowService,
        WorkflowTemplateService $templateService,
        WorkflowNotificationService $notificationService,
        WorkflowProgressService $progressService
    ) {
        $this->workflowService = $workflowService;
        $this->templateService = $templateService;
        $this->notificationService = $notificationService;
        $this->progressService = $progressService;
        $this->middleware('auth');
    }

    /**
     * Create a new sequential workflow
     */
    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'document_id' => 'required|exists:documents,id',
            'recipients' => 'required|array|min:1',
            'recipients.*.user_id' => 'required|exists:users,id',
            'recipients.*.office_id' => 'nullable|exists:offices,id',
            'recipients.*.step_name' => 'nullable|string',
            'recipients.*.instructions' => 'nullable|string',
            'recipients.*.required_action' => 'nullable|in:approve,review,sign,verify,forward',
            'recipients.*.due_days' => 'nullable|integer|min:1|max:30',
            'recipients.*.purpose' => 'nullable|string',
            'recipients.*.remarks' => 'nullable|string',
            'description' => 'nullable|string',
            'urgency' => 'nullable|in:low,medium,high,critical',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $document = Document::findOrFail($request->document_id);
            $user = Auth::user();

            // Check permissions
            if (!$this->canUserCreateWorkflow($user, $document)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to create workflows for this document'
                ], 403);
            }

            $workflowChain = $this->workflowService->createSequentialWorkflow(
                $document,
                $request->recipients,
                $user,
                [
                    'description' => $request->description,
                    'urgency' => $request->urgency ?? 'medium'
                ]
            );

            // Send notifications for first step
            $firstStep = $workflowChain->documentWorkflows()->where('is_current_step', true)->first();
            if ($firstStep) {
                $this->notificationService->notifyStepActivated($firstStep);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sequential workflow created successfully',
                'data' => [
                    'workflow_chain_id' => $workflowChain->id,
                    'total_steps' => $workflowChain->total_steps,
                    'progress' => $this->progressService->getDocumentWorkflowProgress($document)
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create sequential workflow',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create workflow from template
     */
    public function createFromTemplate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'template_id' => 'required|exists:workflow_templates,id',
            'document_id' => 'required|exists:documents,id',
            'recipients' => 'nullable|array',
            'recipients.*.user_id' => 'required_with:recipients|exists:users,id',
            'description' => 'nullable|string',
            'urgency' => 'nullable|in:low,medium,high,critical',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $template = WorkflowTemplate::findOrFail($request->template_id);
            $document = Document::findOrFail($request->document_id);
            $user = Auth::user();

            // Check permissions
            if (!$this->canUserUseTemplate($user, $template)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to use this template'
                ], 403);
            }

            $overrides = [
                'description' => $request->description,
                'urgency' => $request->urgency ?? 'medium',
            ];

            if ($request->has('recipients')) {
                $overrides['recipients'] = $request->recipients;
            }

            $workflowChain = $this->workflowService->createFromTemplate(
                $template,
                $document,
                $user,
                $overrides
            );

            // Send notifications for first step
            $firstStep = $workflowChain->documentWorkflows()->where('is_current_step', true)->first();
            if ($firstStep) {
                $this->notificationService->notifyStepActivated($firstStep);
            }

            return response()->json([
                'success' => true,
                'message' => 'Workflow created from template successfully',
                'data' => [
                    'workflow_chain_id' => $workflowChain->id,
                    'template_name' => $template->name,
                    'total_steps' => $workflowChain->total_steps,
                    'progress' => $this->progressService->getDocumentWorkflowProgress($document)
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create workflow from template',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process a workflow step (approve, reject, etc.)
     */
    public function processStep(Request $request, DocumentWorkflow $workflow): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approved,rejected,returned,forwarded,commented,acknowledged',
            'remarks' => 'nullable|string',
            'rejection_action' => 'nullable|required_if:action,rejected|in:restart,return_to_sender,pause',
            'return_reason' => 'nullable|required_if:rejection_action,return_to_sender|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();

            // Check permissions
            if (!$this->canUserProcessWorkflow($user, $workflow)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to process this workflow step'
                ], 403);
            }

            // Validate workflow is in processable state
            if (!$workflow->is_current_step) {
                return response()->json([
                    'success' => false,
                    'message' => 'This workflow step is not currently active'
                ], 400);
            }

            if ($workflow->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This workflow step has already been processed'
                ], 400);
            }

            // Process the step
            $success = $this->workflowService->processStepCompletion(
                $workflow,
                $request->action,
                [
                    'remarks' => $request->remarks,
                    'rejection_action' => $request->rejection_action,
                    'return_reason' => $request->return_reason,
                    'processed_by' => $user->id,
                ]
            );

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process workflow step'
                ], 500);
            }

            // Send notifications
            $this->notificationService->notifyStepCompleted($workflow, $request->action, [
                'remarks' => $request->remarks,
            ]);

            // Track timing
            $this->progressService->trackStepTiming($workflow, $request->action);

            // Get updated progress
            $workflowChain = $workflow->workflowChain;
            $progress = $this->workflowService->getWorkflowProgress($workflowChain);

            return response()->json([
                'success' => true,
                'message' => 'Workflow step processed successfully',
                'data' => [
                    'action' => $request->action,
                    'workflow_progress' => $progress,
                    'next_step' => $workflowChain->status === 'active' ? $workflowChain->current_step : null,
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process workflow step',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get workflow progress for a document
     */
    public function getProgress(Document $document): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check permissions
            if (!$this->canUserViewDocument($user, $document)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to view this document'
                ], 403);
            }

            $progress = $this->progressService->getDocumentWorkflowProgress($document);

            return response()->json([
                'success' => true,
                'data' => $progress
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get workflow progress',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get workflow chain details
     */
    public function getChain(WorkflowChain $workflowChain): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check permissions
            if (!$this->canUserViewWorkflowChain($user, $workflowChain)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to view this workflow chain'
                ], 403);
            }

            $progress = $this->workflowService->getWorkflowProgress($workflowChain);

            return response()->json([
                'success' => true,
                'data' => $progress
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get workflow chain details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's dashboard data
     */
    public function getDashboard(): JsonResponse
    {
        try {
            $user = Auth::user();
            $dashboardData = $this->progressService->getDashboardData($user);

            return response()->json([
                'success' => true,
                'data' => $dashboardData
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get workflow analytics for a user
     */
    public function getAnalytics(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            $filters = $request->only(['date_from', 'date_to']);
            
            $analytics = $this->progressService->getUserWorkflowAnalytics($user, $filters);

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get workflow analytics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pause/Resume workflow chain
     */
    public function togglePause(WorkflowChain $workflowChain): JsonResponse
    {
        try {
            $user = Auth::user();

            // Check permissions
            if (!$this->canUserManageWorkflowChain($user, $workflowChain)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to manage this workflow'
                ], 403);
            }

            $newStatus = $workflowChain->status === 'paused' ? 'active' : 'paused';
            $workflowChain->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => "Workflow {$newStatus} successfully",
                'data' => [
                    'status' => $newStatus,
                    'workflow_chain_id' => $workflowChain->id
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle workflow status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel workflow chain
     */
    public function cancel(WorkflowChain $workflowChain, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = Auth::user();

            // Check permissions
            if (!$this->canUserManageWorkflowChain($user, $workflowChain)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to cancel this workflow'
                ], 403);
            }

            if ($workflowChain->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel a completed workflow'
                ], 400);
            }

            DB::beginTransaction();

            // Update workflow chain
            $workflowChain->update([
                'status' => 'cancelled',
                'completed_at' => now()
            ]);

            // Cancel all pending steps
            DocumentWorkflow::where('workflow_chain_id', $workflowChain->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'cancelled',
                    'remarks' => 'Workflow cancelled: ' . $request->reason,
                    'is_current_step' => false,
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Workflow cancelled successfully',
                'data' => [
                    'workflow_chain_id' => $workflowChain->id,
                    'cancelled_at' => now(),
                    'reason' => $request->reason
                ]
            ]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel workflow',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Permission helper methods

    private function canUserCreateWorkflow($user, $document): bool
    {
        // User can create workflow if they can edit the document
        return $user->id === $document->created_by || 
               $user->hasRole(['admin', 'manager']) ||
               $user->company_id === $document->company_id;
    }

    private function canUserProcessWorkflow($user, $workflow): bool
    {
        return $user->id === $workflow->recipient_id;
    }

    private function canUserViewDocument($user, $document): bool
    {
        return $user->id === $document->created_by ||
               $user->company_id === $document->company_id ||
               $user->hasRole(['admin', 'super_admin']);
    }

    private function canUserViewWorkflowChain($user, $workflowChain): bool
    {
        return $user->id === $workflowChain->created_by ||
               $workflowChain->documentWorkflows()->where('recipient_id', $user->id)->exists() ||
               $this->canUserViewDocument($user, $workflowChain->document);
    }

    private function canUserManageWorkflowChain($user, $workflowChain): bool
    {
        return $user->id === $workflowChain->created_by ||
               $user->hasRole(['admin', 'super_admin']);
    }

    private function canUserUseTemplate($user, $template): bool
    {
        return $template->is_public ||
               $user->company_id === $template->company_id ||
               $user->id === $template->created_by;
    }
}
