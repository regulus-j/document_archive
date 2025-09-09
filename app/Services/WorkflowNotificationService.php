<?php

namespace App\Services;

use App\Models\DocumentWorkflow;
use App\Models\WorkflowChain;
use App\Models\User;
use App\Models\Document;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class WorkflowNotificationService
{
    /**
     * Send notification when workflow step becomes active
     *
     * @param DocumentWorkflow $workflow
     * @return void
     */
    public function notifyStepActivated(DocumentWorkflow $workflow): void
    {
        try {
            $recipient = $workflow->recipient;
            $document = $workflow->document;
            $sender = $workflow->sender;
            
            if (!$recipient || !$document) {
                Log::warning("Missing recipient or document for workflow notification", [
                    'workflow_id' => $workflow->id
                ]);
                return;
            }
            
            // Prepare notification data
            $notificationData = [
                'type' => 'workflow_step_activated',
                'workflow_id' => $workflow->id,
                'document_title' => $document->title,
                'document_id' => $document->id,
                'tracking_number' => $workflow->tracking_number,
                'sender_name' => $sender->name,
                'step_order' => $workflow->step_order,
                'purpose' => $workflow->purpose,
                'urgency' => $workflow->urgency,
                'due_date' => $workflow->due_date,
                'instructions' => $this->getWorkflowInstructions($workflow),
                'workflow_type' => $workflow->workflow_type,
                'action_required' => $this->getRequiredAction($workflow),
                'document_url' => route('documents.show', $document->id),
                'workflow_url' => route('workflows.show', $workflow->id),
            ];
            
            // Send email notification
            $this->sendEmailNotification($recipient, 'workflow.step-activated', $notificationData);
            
            // Send in-app notification
            $this->sendInAppNotification($recipient, $notificationData);
            
            // Send real-time notification
            $this->sendRealTimeNotification($recipient, $notificationData);
            
            Log::info("Workflow step activation notifications sent", [
                'workflow_id' => $workflow->id,
                'recipient_id' => $recipient->id
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to send workflow step activation notification", [
                'workflow_id' => $workflow->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Send notification when workflow step is completed
     *
     * @param DocumentWorkflow $workflow
     * @param string $action
     * @param array $data
     * @return void
     */
    public function notifyStepCompleted(DocumentWorkflow $workflow, string $action, array $data = []): void
    {
        try {
            $workflowChain = $workflow->workflowChain;
            $document = $workflow->document;
            $recipient = $workflow->recipient;
            $sender = $workflow->sender;
            
            // Notify the workflow creator/sender
            $notificationData = [
                'type' => 'workflow_step_completed',
                'workflow_id' => $workflow->id,
                'document_title' => $document->title,
                'document_id' => $document->id,
                'tracking_number' => $workflow->tracking_number,
                'recipient_name' => $recipient->name,
                'step_order' => $workflow->step_order,
                'action_taken' => $action,
                'remarks' => $data['remarks'] ?? null,
                'completed_at' => now(),
                'workflow_type' => $workflow->workflow_type,
                'document_url' => route('documents.show', $document->id),
                'workflow_url' => route('workflows.show', $workflow->id),
            ];
            
            // Send to workflow creator
            $this->sendEmailNotification($sender, 'workflow.step-completed', $notificationData);
            $this->sendInAppNotification($sender, $notificationData);
            
            // If workflow chain has next step, notify next recipient
            if ($workflowChain && $workflowChain->status === 'active') {
                $nextWorkflow = DocumentWorkflow::where('workflow_chain_id', $workflowChain->id)
                    ->where('is_current_step', true)
                    ->first();
                    
                if ($nextWorkflow && $nextWorkflow->id !== $workflow->id) {
                    $this->notifyStepActivated($nextWorkflow);
                }
            }
            
            Log::info("Workflow step completion notifications sent", [
                'workflow_id' => $workflow->id,
                'action' => $action
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to send workflow step completion notification", [
                'workflow_id' => $workflow->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Send notification when entire workflow is completed
     *
     * @param WorkflowChain $workflowChain
     * @return void
     */
    public function notifyWorkflowCompleted(WorkflowChain $workflowChain): void
    {
        try {
            $document = $workflowChain->document;
            $creator = $workflowChain->creator;
            
            $notificationData = [
                'type' => 'workflow_completed',
                'workflow_chain_id' => $workflowChain->id,
                'document_title' => $document->title,
                'document_id' => $document->id,
                'total_steps' => $workflowChain->total_steps,
                'completed_at' => $workflowChain->completed_at,
                'started_at' => $workflowChain->started_at,
                'duration' => $this->calculateWorkflowDuration($workflowChain),
                'workflow_type' => $workflowChain->workflow_type,
                'document_url' => route('documents.show', $document->id),
                'workflow_summary_url' => route('workflows.summary', $workflowChain->id),
            ];
            
            // Send to workflow creator
            $this->sendEmailNotification($creator, 'workflow.completed', $notificationData);
            $this->sendInAppNotification($creator, $notificationData);
            
            // Send to all participants
            $this->notifyAllParticipants($workflowChain, $notificationData);
            
            Log::info("Workflow completion notifications sent", [
                'workflow_chain_id' => $workflowChain->id
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to send workflow completion notification", [
                'workflow_chain_id' => $workflowChain->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Send reminder notifications for overdue workflows
     *
     * @return void
     */
    public function sendOverdueReminders(): void
    {
        try {
            $overdueWorkflows = DocumentWorkflow::where('is_current_step', true)
                ->where('status', 'pending')
                ->where('due_date', '<', now()->toDateString())
                ->with(['recipient', 'document', 'sender'])
                ->get();
                
            foreach ($overdueWorkflows as $workflow) {
                $daysOverdue = now()->diffInDays($workflow->due_date);
                
                $notificationData = [
                    'type' => 'workflow_overdue',
                    'workflow_id' => $workflow->id,
                    'document_title' => $workflow->document->title,
                    'tracking_number' => $workflow->tracking_number,
                    'days_overdue' => $daysOverdue,
                    'due_date' => $workflow->due_date,
                    'urgency' => $workflow->urgency,
                    'sender_name' => $workflow->sender->name,
                    'document_url' => route('documents.show', $workflow->document->id),
                    'workflow_url' => route('workflows.show', $workflow->id),
                ];
                
                $this->sendEmailNotification($workflow->recipient, 'workflow.overdue', $notificationData);
                $this->sendInAppNotification($workflow->recipient, $notificationData);
                
                // Also notify supervisor if severely overdue
                if ($daysOverdue > 3) {
                    $this->notifySupervisor($workflow, $notificationData);
                }
            }
            
            Log::info("Sent overdue reminders for " . $overdueWorkflows->count() . " workflows");
            
        } catch (\Exception $e) {
            Log::error("Failed to send overdue reminders", [
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Send daily digest of pending workflows to users
     *
     * @return void
     */
    public function sendDailyDigest(): void
    {
        try {
            // Get all users with pending workflows
            $usersWithPendingWorkflows = User::whereHas('receivedWorkflows', function($query) {
                $query->where('is_current_step', true)
                      ->where('status', 'pending');
            })->with(['receivedWorkflows' => function($query) {
                $query->where('is_current_step', true)
                      ->where('status', 'pending')
                      ->with(['document', 'sender']);
            }])->get();
            
            foreach ($usersWithPendingWorkflows as $user) {
                $pendingWorkflows = $user->receivedWorkflows;
                
                $digestData = [
                    'type' => 'workflow_daily_digest',
                    'user_name' => $user->name,
                    'total_pending' => $pendingWorkflows->count(),
                    'overdue_count' => $pendingWorkflows->where('due_date', '<', now()->toDateString())->count(),
                    'urgent_count' => $pendingWorkflows->where('urgency', 'critical')->count(),
                    'workflows' => $pendingWorkflows->map(function($workflow) {
                        return [
                            'document_title' => $workflow->document->title,
                            'tracking_number' => $workflow->tracking_number,
                            'sender_name' => $workflow->sender->name,
                            'due_date' => $workflow->due_date,
                            'urgency' => $workflow->urgency,
                            'days_until_due' => now()->diffInDays($workflow->due_date, false),
                            'workflow_url' => route('workflows.show', $workflow->id),
                        ];
                    })->toArray(),
                    'dashboard_url' => route('dashboard'),
                ];
                
                $this->sendEmailNotification($user, 'workflow.daily-digest', $digestData);
            }
            
            Log::info("Sent daily digest to " . $usersWithPendingWorkflows->count() . " users");
            
        } catch (\Exception $e) {
            Log::error("Failed to send daily digest", [
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Send escalation notification to supervisors
     *
     * @param DocumentWorkflow $workflow
     * @param array $escalationData
     * @return void
     */
    public function sendEscalationNotification(DocumentWorkflow $workflow, array $escalationData): void
    {
        try {
            $supervisor = $this->getSupervisor($workflow->recipient);
            
            if (!$supervisor) {
                Log::warning("No supervisor found for escalation", [
                    'workflow_id' => $workflow->id,
                    'recipient_id' => $workflow->recipient_id
                ]);
                return;
            }
            
            $notificationData = [
                'type' => 'workflow_escalation',
                'workflow_id' => $workflow->id,
                'document_title' => $workflow->document->title,
                'tracking_number' => $workflow->tracking_number,
                'recipient_name' => $workflow->recipient->name,
                'escalation_reason' => $escalationData['reason'] ?? 'Overdue workflow',
                'days_overdue' => $escalationData['days_overdue'] ?? 0,
                'urgency' => $workflow->urgency,
                'document_url' => route('documents.show', $workflow->document->id),
                'workflow_url' => route('workflows.show', $workflow->id),
            ];
            
            $this->sendEmailNotification($supervisor, 'workflow.escalation', $notificationData);
            $this->sendInAppNotification($supervisor, $notificationData);
            
            Log::info("Escalation notification sent", [
                'workflow_id' => $workflow->id,
                'supervisor_id' => $supervisor->id
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to send escalation notification", [
                'workflow_id' => $workflow->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    // Helper methods
    
    private function sendEmailNotification(User $user, string $template, array $data): void
    {
        try {
            // Use Laravel's Mail facade to send email
            Mail::to($user->email)->queue(new \App\Mail\WorkflowNotificationMail($template, $data));
        } catch (\Exception $e) {
            Log::error("Failed to send email notification", [
                'user_id' => $user->id,
                'template' => $template,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function sendInAppNotification(User $user, array $data): void
    {
        try {
            // Create in-app notification record
            $user->notifications()->create([
                'type' => $data['type'],
                'data' => json_encode($data),
                'read_at' => null,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to send in-app notification", [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function sendRealTimeNotification(User $user, array $data): void
    {
        try {
            // Use broadcasting for real-time notifications
            broadcast(new \App\Events\WorkflowNotificationEvent($user->id, $data));
        } catch (\Exception $e) {
            Log::error("Failed to send real-time notification", [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function getWorkflowInstructions(DocumentWorkflow $workflow): ?string
    {
        $config = json_decode($workflow->workflow_config, true);
        return $config['instructions'] ?? null;
    }
    
    private function getRequiredAction(DocumentWorkflow $workflow): string
    {
        $config = json_decode($workflow->workflow_config, true);
        return $config['required_action'] ?? 'approve';
    }
    
    private function calculateWorkflowDuration(WorkflowChain $workflowChain): array
    {
        $start = $workflowChain->started_at;
        $end = $workflowChain->completed_at;
        
        if (!$start || !$end) {
            return ['days' => 0, 'hours' => 0, 'minutes' => 0];
        }
        
        $diff = $start->diff($end);
        
        return [
            'days' => $diff->days,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'total_hours' => ($diff->days * 24) + $diff->h + ($diff->i / 60),
        ];
    }
    
    private function notifyAllParticipants(WorkflowChain $workflowChain, array $baseData): void
    {
        $participants = User::whereIn('id', function($query) use ($workflowChain) {
            $query->select('recipient_id')
                  ->from('document_workflows')
                  ->where('workflow_chain_id', $workflowChain->id)
                  ->distinct();
        })->get();
        
        foreach ($participants as $participant) {
            $participantData = array_merge($baseData, [
                'participant_name' => $participant->name,
            ]);
            
            $this->sendInAppNotification($participant, $participantData);
        }
    }
    
    private function notifySupervisor(DocumentWorkflow $workflow, array $notificationData): void
    {
        $supervisor = $this->getSupervisor($workflow->recipient);
        
        if ($supervisor) {
            $this->sendEmailNotification($supervisor, 'workflow.overdue-escalation', $notificationData);
            $this->sendInAppNotification($supervisor, $notificationData);
        }
    }
    
    private function getSupervisor(User $user): ?User
    {
        // This would depend on your organizational structure
        // For now, return a simple implementation
        return $user->supervisor ?? $user->office?->head ?? null;
    }
}
