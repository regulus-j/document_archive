<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentWorkflow;
use App\Models\WorkflowChain;
use App\Models\WorkflowTemplate;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class SequentialWorkflowService
{
    /**
     * Create a new sequential workflow chain
     *
     * @param Document $document
     * @param array $recipients Array of recipient data with order
     * @param User $sender
     * @param array $options Additional workflow options
     * @return WorkflowChain
     * @throws Exception
     */
    public function createSequentialWorkflow(Document $document, array $recipients, User $sender, array $options = []): WorkflowChain
    {
        DB::beginTransaction();
        
        try {
            // Validate recipients structure
            $this->validateRecipients($recipients);
            
            // Create the workflow chain
            $workflowChain = WorkflowChain::create([
                'document_id' => $document->id,
                'created_by' => $sender->id,
                'workflow_type' => 'sequential',
                'current_step' => 1,
                'total_steps' => count($recipients),
                'status' => 'active',
                'description' => $options['description'] ?? "Sequential workflow for document: {$document->title}",
                'step_config' => json_encode($this->buildStepConfig($recipients)),
                'started_at' => now(),
            ]);
            
            // Create individual workflow steps
            foreach ($recipients as $index => $recipient) {
                $stepOrder = $index + 1;
                $isCurrentStep = $stepOrder === 1; // First step is active
                
                DocumentWorkflow::create([
                    'tracking_number' => $this->generateTrackingNumber($document, $stepOrder),
                    'workflow_chain_id' => $workflowChain->id,
                    'document_id' => $document->id,
                    'sender_id' => $sender->id,
                    'recipient_id' => $recipient['user_id'],
                    'recipient_office' => $recipient['office_id'] ?? null,
                    'step_order' => $stepOrder,
                    'is_current_step' => $isCurrentStep,
                    'workflow_type' => 'sequential',
                    'workflow_group_id' => 1, // Sequential workflows use group 1
                    'completion_action' => $stepOrder < count($recipients) ? 'proceed' : 'wait_all',
                    'workflow_config' => json_encode([
                        'step_name' => $recipient['step_name'] ?? "Step {$stepOrder}",
                        'instructions' => $recipient['instructions'] ?? '',
                        'required_action' => $recipient['required_action'] ?? 'approve',
                        'auto_proceed' => $recipient['auto_proceed'] ?? false,
                        'due_days' => $recipient['due_days'] ?? 5,
                    ]),
                    'depends_on_step' => $stepOrder > 1 ? $stepOrder - 1 : null,
                    'status' => $isCurrentStep ? 'pending' : 'pending', // All start as pending, but only current is active
                    'purpose' => $recipient['purpose'] ?? 'Review and process',
                    'urgency' => $options['urgency'] ?? 'medium',
                    'due_date' => $this->calculateDueDate($recipient['due_days'] ?? 5),
                    'remarks' => $recipient['remarks'] ?? null,
                ]);
            }
            
            DB::commit();
            Log::info("Sequential workflow created", [
                'workflow_chain_id' => $workflowChain->id,
                'document_id' => $document->id,
                'total_steps' => count($recipients)
            ]);
            
            return $workflowChain;
            
        } catch (Exception $e) {
            DB::rollback();
            Log::error("Failed to create sequential workflow", [
                'document_id' => $document->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
     * Process completion of a workflow step and advance to next
     *
     * @param DocumentWorkflow $workflow
     * @param string $action The action taken (approved, rejected, etc.)
     * @param array $data Additional data
     * @return bool
     * @throws Exception
     */
    public function processStepCompletion(DocumentWorkflow $workflow, string $action, array $data = []): bool
    {
        DB::beginTransaction();
        
        try {
            // Validate this is a sequential workflow
            if ($workflow->workflow_type !== 'sequential') {
                throw new Exception("This workflow is not sequential");
            }
            
            // Validate this is the current step
            if (!$workflow->is_current_step) {
                throw new Exception("This step is not currently active");
            }
            
            $workflowChain = $workflow->workflowChain;
            
            if (!$workflowChain) {
                throw new Exception("Workflow chain not found");
            }
            
            // Update current step
            $workflow->update([
                'status' => $action,
                'is_current_step' => false,
                'received_at' => now(),
                'remarks' => $data['remarks'] ?? $workflow->remarks,
            ]);
            
            // Handle different completion scenarios
            if (in_array($action, ['approved', 'forwarded'])) {
                return $this->advanceToNextStep($workflowChain);
            } elseif (in_array($action, ['rejected', 'returned'])) {
                return $this->handleRejection($workflowChain, $data);
            } else {
                // For other actions, workflow might continue or pause
                return $this->handleOtherAction($workflowChain, $action, $data);
            }
            
        } catch (Exception $e) {
            DB::rollback();
            Log::error("Failed to process step completion", [
                'workflow_id' => $workflow->id,
                'action' => $action,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
     * Advance workflow to the next step
     */
    private function advanceToNextStep(WorkflowChain $workflowChain): bool
    {
        $currentStep = $workflowChain->current_step;
        $nextStep = $currentStep + 1;
        
        if ($nextStep > $workflowChain->total_steps) {
            // Workflow complete
            $workflowChain->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            
            Log::info("Sequential workflow completed", [
                'workflow_chain_id' => $workflowChain->id,
                'document_id' => $workflowChain->document_id
            ]);
            
            DB::commit();
            return true;
        }
        
        // Update workflow chain to next step
        $workflowChain->update([
            'current_step' => $nextStep
        ]);
        
        // Activate next step
        $nextWorkflow = DocumentWorkflow::where('workflow_chain_id', $workflowChain->id)
            ->where('step_order', $nextStep)
            ->first();
            
        if ($nextWorkflow) {
            $nextWorkflow->update([
                'is_current_step' => true,
                'status' => 'pending'
            ]);
            
            Log::info("Sequential workflow advanced", [
                'workflow_chain_id' => $workflowChain->id,
                'from_step' => $currentStep,
                'to_step' => $nextStep
            ]);
        }
        
        DB::commit();
        return true;
    }
    
    /**
     * Handle workflow rejection
     */
    private function handleRejection(WorkflowChain $workflowChain, array $data): bool
    {
        $rejectionAction = $data['rejection_action'] ?? 'pause';
        
        if ($rejectionAction === 'restart') {
            // Restart workflow from beginning
            return $this->restartWorkflow($workflowChain);
        } elseif ($rejectionAction === 'return_to_sender') {
            // Return to document sender
            return $this->returnToSender($workflowChain, $data);
        } else {
            // Pause workflow
            $workflowChain->update([
                'status' => 'paused'
            ]);
            
            DB::commit();
            return true;
        }
    }
    
    /**
     * Handle other workflow actions
     */
    private function handleOtherAction(WorkflowChain $workflowChain, string $action, array $data): bool
    {
        // For actions like 'commented', 'acknowledged', workflow continues
        if (in_array($action, ['commented', 'acknowledged'])) {
            // These actions don't advance the workflow, just update status
            DB::commit();
            return true;
        }
        
        // For other actions, pause for manual intervention
        $workflowChain->update([
            'status' => 'paused'
        ]);
        
        DB::commit();
        return true;
    }
    
    /**
     * Get workflow progress information
     */
    public function getWorkflowProgress(WorkflowChain $workflowChain): array
    {
        $workflows = $workflowChain->documentWorkflows()
            ->orderBy('step_order')
            ->get();
            
        $progress = [
            'chain_id' => $workflowChain->id,
            'document_id' => $workflowChain->document_id,
            'current_step' => $workflowChain->current_step,
            'total_steps' => $workflowChain->total_steps,
            'status' => $workflowChain->status,
            'progress_percentage' => ($workflowChain->current_step / $workflowChain->total_steps) * 100,
            'steps' => []
        ];
        
        foreach ($workflows as $workflow) {
            $progress['steps'][] = [
                'step_order' => $workflow->step_order,
                'recipient' => $workflow->recipient->name ?? 'Unknown',
                'recipient_office' => $workflow->recipientOffice->name ?? null,
                'status' => $workflow->status,
                'is_current' => $workflow->is_current_step,
                'due_date' => $workflow->due_date,
                'completed_at' => $workflow->received_at,
                'purpose' => $workflow->purpose,
                'urgency' => $workflow->urgency,
            ];
        }
        
        return $progress;
    }
    
    /**
     * Create workflow from template
     */
    public function createFromTemplate(WorkflowTemplate $template, Document $document, User $sender, array $overrides = []): WorkflowChain
    {
        $stepsConfig = json_decode($template->steps_config, true);
        
        // Convert template config to recipients array
        $recipients = [];
        foreach ($stepsConfig as $step) {
            $recipients[] = [
                'user_id' => $step['user_id'] ?? null,
                'office_id' => $step['office_id'] ?? null,
                'step_name' => $step['step_name'] ?? "Step {$step['order']}",
                'instructions' => $step['instructions'] ?? '',
                'required_action' => $step['required_action'] ?? 'approve',
                'due_days' => $step['due_days'] ?? 5,
                'purpose' => $step['purpose'] ?? 'Review and process',
            ];
        }
        
        // Apply any overrides
        if (!empty($overrides['recipients'])) {
            $recipients = array_merge($recipients, $overrides['recipients']);
        }
        
        // Create workflow
        $workflowChain = $this->createSequentialWorkflow(
            $document, 
            $recipients, 
            $sender,
            array_merge([
                'description' => "Workflow from template: {$template->name}",
                'template_id' => $template->id
            ], $overrides)
        );
        
        // Update template usage
        $template->increment('usage_count');
        
        return $workflowChain;
    }
    
    // Helper methods
    
    private function validateRecipients(array $recipients): void
    {
        if (empty($recipients)) {
            throw new Exception("At least one recipient is required");
        }
        
        foreach ($recipients as $index => $recipient) {
            if (empty($recipient['user_id'])) {
                throw new Exception("Recipient at position {$index} must have a user_id");
            }
        }
    }
    
    private function buildStepConfig(array $recipients): array
    {
        $config = [];
        foreach ($recipients as $index => $recipient) {
            $config[] = [
                'order' => $index + 1,
                'user_id' => $recipient['user_id'],
                'office_id' => $recipient['office_id'] ?? null,
                'step_name' => $recipient['step_name'] ?? "Step " . ($index + 1),
                'required_action' => $recipient['required_action'] ?? 'approve',
                'auto_proceed' => $recipient['auto_proceed'] ?? false,
            ];
        }
        return $config;
    }
    
    private function generateTrackingNumber(Document $document, int $stepOrder): string
    {
        return $document->tracking_number . '-S' . str_pad($stepOrder, 2, '0', STR_PAD_LEFT);
    }
    
    private function calculateDueDate(int $days): string
    {
        return now()->addDays($days)->toDateString();
    }
    
    private function restartWorkflow(WorkflowChain $workflowChain): bool
    {
        // Reset all steps
        DocumentWorkflow::where('workflow_chain_id', $workflowChain->id)
            ->update([
                'status' => 'pending',
                'is_current_step' => false,
                'received_at' => null,
            ]);
            
        // Activate first step
        DocumentWorkflow::where('workflow_chain_id', $workflowChain->id)
            ->where('step_order', 1)
            ->update(['is_current_step' => true]);
            
        // Reset chain
        $workflowChain->update([
            'current_step' => 1,
            'status' => 'active',
            'completed_at' => null,
        ]);
        
        DB::commit();
        return true;
    }
    
    private function returnToSender(WorkflowChain $workflowChain, array $data): bool
    {
        // Create a return workflow step to sender
        DocumentWorkflow::create([
            'tracking_number' => $this->generateTrackingNumber($workflowChain->document, 0),
            'workflow_chain_id' => $workflowChain->id,
            'document_id' => $workflowChain->document_id,
            'sender_id' => $workflowChain->created_by,
            'recipient_id' => $workflowChain->created_by,
            'step_order' => 0, // Special step for returns
            'is_current_step' => true,
            'workflow_type' => 'sequential',
            'status' => 'returned',
            'purpose' => 'Document returned for revision',
            'remarks' => $data['return_reason'] ?? 'Document returned',
        ]);
        
        $workflowChain->update([
            'status' => 'paused',
            'current_step' => 0
        ]);
        
        DB::commit();
        return true;
    }
}
