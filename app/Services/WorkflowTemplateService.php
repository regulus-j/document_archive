<?php

namespace App\Services;

use App\Models\WorkflowTemplate;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class WorkflowTemplateService
{
    /**
     * Create a new workflow template
     *
     * @param array $data Template data
     * @param User $creator
     * @return WorkflowTemplate
     * @throws Exception
     */
    public function createTemplate(array $data, User $creator): WorkflowTemplate
    {
        DB::beginTransaction();
        
        try {
            $this->validateTemplateData($data);
            
            $template = WorkflowTemplate::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? '',
                'company_id' => $creator->company_id ?? null,
                'created_by' => $creator->id,
                'workflow_type' => $data['workflow_type'] ?? 'sequential',
                'steps_config' => json_encode($this->processStepsConfig($data['steps'])),
                'is_active' => $data['is_active'] ?? true,
                'is_public' => $data['is_public'] ?? false,
                'usage_count' => 0,
            ]);
            
            DB::commit();
            
            Log::info("Workflow template created", [
                'template_id' => $template->id,
                'name' => $template->name,
                'created_by' => $creator->id
            ]);
            
            return $template;
            
        } catch (Exception $e) {
            DB::rollback();
            
            Log::error("Failed to create workflow template", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'data' => $data,
                'creator_id' => $creator->id
            ]);
            
            throw new Exception("Template creation failed: " . $e->getMessage(), 0, $e);
        }
    }
    
    /**
     * Update an existing workflow template
     *
     * @param WorkflowTemplate $template
     * @param array $data
     * @param User $user
     * @return WorkflowTemplate
     * @throws Exception
     */
    public function updateTemplate(WorkflowTemplate $template, array $data, User $user): WorkflowTemplate
    {
        // Check permissions
        if (!$this->canUserEditTemplate($template, $user)) {
            throw new Exception("You don't have permission to edit this template");
        }
        
        DB::beginTransaction();
        
        try {
            $updateData = [];
            
            if (isset($data['name'])) {
                $updateData['name'] = $data['name'];
            }
            
            if (isset($data['description'])) {
                $updateData['description'] = $data['description'];
            }
            
            if (isset($data['steps'])) {
                $this->validateStepsConfig($data['steps']);
                $updateData['steps_config'] = json_encode($this->processStepsConfig($data['steps']));
            }
            
            if (isset($data['is_active'])) {
                $updateData['is_active'] = $data['is_active'];
            }
            
            if (isset($data['is_public'])) {
                $updateData['is_public'] = $data['is_public'];
            }
            
            $template->update($updateData);
            
            DB::commit();
            
            Log::info("Workflow template updated", [
                'template_id' => $template->id,
                'updated_by' => $user->id
            ]);
            
            return $template->refresh();
            
        } catch (Exception $e) {
            DB::rollback();
            Log::error("Failed to update workflow template", [
                'template_id' => $template->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
     * Get templates available to a user
     *
     * @param User $user
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAvailableTemplates(User $user, array $filters = [])
    {
        $query = WorkflowTemplate::where(function($q) use ($user) {
            // Show templates from same company (if user has company_id) or public templates
            if ($user->company_id) {
                $q->where('company_id', $user->company_id)
                  ->orWhere('is_public', true);
            } else {
                // If user has no company_id, show public templates and templates with no company_id
                $q->whereNull('company_id')
                  ->orWhere('is_public', true);
            }
        })
        ->where('is_active', true);
        
        if (!empty($filters['workflow_type'])) {
            $query->where('workflow_type', $filters['workflow_type']);
        }
        
        if (!empty($filters['created_by_me'])) {
            $query->where('created_by', $user->id);
        }
        
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }
        
        return $query->with(['creator', 'company'])
                    ->orderBy('usage_count', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
    }
    
    /**
     * Clone a template for customization
     *
     * @param WorkflowTemplate $template
     * @param User $user
     * @param array $modifications
     * @return WorkflowTemplate
     */
    public function cloneTemplate(WorkflowTemplate $template, User $user, array $modifications = []): WorkflowTemplate
    {
        $cloneData = [
            'name' => $modifications['name'] ?? $template->name . ' (Copy)',
            'description' => $modifications['description'] ?? $template->description,
            'workflow_type' => $template->workflow_type,
            'steps' => json_decode($template->steps_config, true),
            'is_active' => true,
            'is_public' => false,
        ];
        
        // Apply modifications
        if (!empty($modifications['steps'])) {
            $cloneData['steps'] = $modifications['steps'];
        }
        
        return $this->createTemplate($cloneData, $user);
    }
    
    /**
     * Generate template from existing workflow
     *
     * @param \App\Models\WorkflowChain $workflowChain
     * @param User $user
     * @param array $templateData
     * @return WorkflowTemplate
     */
    public function createTemplateFromWorkflow($workflowChain, User $user, array $templateData): WorkflowTemplate
    {
        $workflows = $workflowChain->documentWorkflows()
                                  ->orderBy('step_order')
                                  ->get();
        
        $steps = [];
        foreach ($workflows as $workflow) {
            $workflowConfig = json_decode($workflow->workflow_config, true) ?? [];
            
            $steps[] = [
                'order' => $workflow->step_order,
                'step_name' => $workflowConfig['step_name'] ?? "Step {$workflow->step_order}",
                'role' => $this->getUserRole($workflow->recipient),
                'required_action' => $workflowConfig['required_action'] ?? 'approve',
                'instructions' => $workflowConfig['instructions'] ?? '',
                'due_days' => $workflowConfig['due_days'] ?? 5,
                'auto_proceed' => $workflowConfig['auto_proceed'] ?? false,
                'purpose' => $workflow->purpose,
            ];
        }
        
        $data = [
            'name' => $templateData['name'],
            'description' => $templateData['description'] ?? "Template created from workflow chain {$workflowChain->id}",
            'workflow_type' => $workflowChain->workflow_type,
            'steps' => $steps,
            'is_active' => true,
            'is_public' => $templateData['is_public'] ?? false,
        ];
        
        return $this->createTemplate($data, $user);
    }
    
    /**
     * Get template usage statistics
     *
     * @param WorkflowTemplate $template
     * @return array
     */
    public function getTemplateStats(WorkflowTemplate $template): array
    {
        // Get workflows created from this template (this would require adding template_id to workflow_chains)
        $totalUsage = $template->usage_count;
        
        // Get recent usage (last 30 days)
        $recentUsage = DB::table('workflow_chains')
            ->where('step_config', 'like', '%template_id":"' . $template->id . '"%')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();
        
        // Get success rate (completed vs total)
        $completedWorkflows = DB::table('workflow_chains')
            ->where('step_config', 'like', '%template_id":"' . $template->id . '"%')
            ->where('status', 'completed')
            ->count();
            
        $successRate = $totalUsage > 0 ? ($completedWorkflows / $totalUsage) * 100 : 0;
        
        // Average completion time
        $avgCompletionTime = DB::table('workflow_chains')
            ->where('step_config', 'like', '%template_id":"' . $template->id . '"%')
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, started_at, completed_at)) as avg_hours')
            ->value('avg_hours') ?? 0;
        
        return [
            'total_usage' => $totalUsage,
            'recent_usage' => $recentUsage,
            'success_rate' => round($successRate, 2),
            'avg_completion_hours' => round($avgCompletionTime, 2),
            'steps_count' => count(json_decode($template->steps_config, true)),
            'created_at' => $template->created_at,
            'last_used' => $template->updated_at,
        ];
    }
    
    /**
     * Delete a template
     *
     * @param WorkflowTemplate $template
     * @param User $user
     * @return bool
     * @throws Exception
     */
    public function deleteTemplate(WorkflowTemplate $template, User $user): bool
    {
        if (!$this->canUserEditTemplate($template, $user)) {
            throw new Exception("You don't have permission to delete this template");
        }
        
        // Check if template is being used in active workflows
        $activeUsage = DB::table('workflow_chains')
            ->where('step_config', 'like', '%template_id":"' . $template->id . '"%')
            ->where('status', 'active')
            ->count();
            
        if ($activeUsage > 0) {
            throw new Exception("Cannot delete template that is being used in active workflows");
        }
        
        $template->delete();
        
        Log::info("Workflow template deleted", [
            'template_id' => $template->id,
            'deleted_by' => $user->id
        ]);
        
        return true;
    }
    
    // Helper methods
    
    private function validateTemplateData(array $data): void
    {
        if (empty($data['name'])) {
            throw new Exception("Template name is required");
        }
        
        if (empty($data['steps']) || !is_array($data['steps'])) {
            throw new Exception("Template must have at least one step");
        }
        
        $this->validateStepsConfig($data['steps']);
    }
    
    private function validateStepsConfig(array $steps): void
    {
        if (empty($steps)) {
            throw new Exception("At least one step is required");
        }
        
        foreach ($steps as $index => $step) {
            // Check for either the new format (name) or old format (step_name)
            if (empty($step['name']) && empty($step['step_name']) && empty($step['role'])) {
                throw new Exception("Step " . ($index + 1) . " must have a name or role defined");
            }
        }
    }
    
    private function processStepsConfig(array $steps): array
    {
        $processed = [];
        
        foreach ($steps as $index => $step) {
            $processed[] = [
                'order' => $step['order_index'] ?? ($index + 1),
                'step_name' => $step['name'] ?? $step['step_name'] ?? "Step " . ($index + 1),
                'role' => $step['role'] ?? null,
                'user_id' => $step['user_id'] ?? null,
                'office_id' => $step['office_id'] ?? null,
                'required_action' => $step['action_type'] ?? $step['required_action'] ?? 'review',
                'instructions' => $step['description'] ?? $step['instructions'] ?? '',
                'due_days' => $step['due_days'] ?? 5,
                'auto_proceed' => $step['auto_proceed'] ?? false,
                'purpose' => $step['purpose'] ?? 'Review and process',
                'is_required' => $step['is_required'] ?? true,
                'allows_comments' => $step['allows_comments'] ?? true,
                'sends_notification' => $step['sends_notification'] ?? true,
                'reminder_days' => $step['reminder_days'] ?? null,
            ];
        }
        
        // Sort by order
        usort($processed, function($a, $b) {
            return $a['order'] <=> $b['order'];
        });
        
        return $processed;
    }
    
    private function canUserEditTemplate(WorkflowTemplate $template, User $user): bool
    {
        // Template creator can always edit
        if ($template->created_by === $user->id) {
            return true;
        }
        
        // Company admin can edit company templates
        if ($template->company_id === $user->company_id && $user->hasRole('admin')) {
            return true;
        }
        
        // System admin can edit any template
        if ($user->hasRole('super_admin')) {
            return true;
        }
        
        return false;
    }
    
    private function getUserRole(User $user): string
    {
        // This would depend on your role system
        // For now, return a generic role based on user attributes
        if ($user->hasRole('admin')) {
            return 'admin_approval';
        } elseif ($user->hasRole('manager')) {
            return 'manager_review';
        } else {
            return 'general_review';
        }
    }
}
