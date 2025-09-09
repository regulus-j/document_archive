<?php

namespace App\Services;

use App\Models\DocumentWorkflow;
use App\Models\WorkflowChain;
use App\Models\Document;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class WorkflowProgressService
{
    /**
     * Get comprehensive workflow progress for a document
     *
     * @param Document $document
     * @return array
     */
    public function getDocumentWorkflowProgress(Document $document): array
    {
        $workflowChains = WorkflowChain::where('document_id', $document->id)
            ->with(['documentWorkflows' => function($query) {
                $query->with(['recipient', 'recipientOffice'])
                      ->orderBy('step_order');
            }])
            ->orderBy('created_at')
            ->get();
        
        $progress = [
            'document_id' => $document->id,
            'document_title' => $document->title,
            'total_chains' => $workflowChains->count(),
            'active_chains' => $workflowChains->where('status', 'active')->count(),
            'completed_chains' => $workflowChains->where('status', 'completed')->count(),
            'overall_progress' => 0,
            'workflow_chains' => [],
            'timeline' => [],
            'current_recipients' => [],
            'bottlenecks' => [],
        ];
        
        $totalSteps = 0;
        $completedSteps = 0;
        
        foreach ($workflowChains as $chain) {
            $chainProgress = $this->getChainProgress($chain);
            $progress['workflow_chains'][] = $chainProgress;
            
            $totalSteps += $chain->total_steps;
            $completedSteps += $chainProgress['completed_steps'];
            
            // Add to timeline
            $progress['timeline'] = array_merge($progress['timeline'], $chainProgress['timeline']);
            
            // Track current recipients
            if ($chain->status === 'active') {
                $currentStep = $chain->documentWorkflows->where('is_current_step', true)->first();
                if ($currentStep) {
                    $progress['current_recipients'][] = [
                        'workflow_id' => $currentStep->id,
                        'recipient' => $currentStep->recipient->name,
                        'office' => $currentStep->recipientOffice->name ?? null,
                        'due_date' => $currentStep->due_date,
                        'urgency' => $currentStep->urgency,
                        'days_pending' => now()->diffInDays($currentStep->created_at),
                    ];
                }
            }
        }
        
        // Calculate overall progress
        if ($totalSteps > 0) {
            $progress['overall_progress'] = ($completedSteps / $totalSteps) * 100;
        }
        
        // Sort timeline by date
        usort($progress['timeline'], function($a, $b) {
            return strtotime($a['timestamp']) <=> strtotime($b['timestamp']);
        });
        
        // Identify bottlenecks
        $progress['bottlenecks'] = $this->identifyBottlenecks($workflowChains);
        
        return $progress;
    }
    
    /**
     * Get workflow analytics for a user
     *
     * @param User $user
     * @param array $filters
     * @return array
     */
    public function getUserWorkflowAnalytics(User $user, array $filters = []): array
    {
        $dateFrom = isset($filters['date_from']) ? Carbon::parse($filters['date_from']) : now()->subMonth();
        $dateTo = isset($filters['date_to']) ? Carbon::parse($filters['date_to']) : now();
        
        $cacheKey = "user_workflow_analytics_{$user->id}_" . $dateFrom->format('Y-m-d') . '_' . $dateTo->format('Y-m-d');
        
        return Cache::remember($cacheKey, 3600, function() use ($user, $dateFrom, $dateTo) {
            // Workflows received by user
            $receivedWorkflows = DocumentWorkflow::where('recipient_id', $user->id)
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->with(['document', 'sender'])
                ->get();
            
            // Workflows sent by user
            $sentWorkflows = DocumentWorkflow::where('sender_id', $user->id)
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->with(['document', 'recipient'])
                ->get();
            
            $analytics = [
                'period' => [
                    'from' => $dateFrom->toDateString(),
                    'to' => $dateTo->toDateString(),
                ],
                'received' => $this->analyzeReceivedWorkflows($receivedWorkflows),
                'sent' => $this->analyzeSentWorkflows($sentWorkflows),
                'performance' => $this->calculateUserPerformance($receivedWorkflows),
                'productivity' => $this->calculateProductivityMetrics($user, $dateFrom, $dateTo),
                'trends' => $this->getWorkflowTrends($user, $dateFrom, $dateTo),
            ];
            
            return $analytics;
        });
    }
    
    /**
     * Get company-wide workflow analytics
     *
     * @param Company $company
     * @param array $filters
     * @return array
     */
    public function getCompanyWorkflowAnalytics(Company $company, array $filters = []): array
    {
        $dateFrom = isset($filters['date_from']) ? Carbon::parse($filters['date_from']) : now()->subMonth();
        $dateTo = isset($filters['date_to']) ? Carbon::parse($filters['date_to']) : now();
        
        $cacheKey = "company_workflow_analytics_{$company->id}_" . $dateFrom->format('Y-m-d') . '_' . $dateTo->format('Y-m-d');
        
        return Cache::remember($cacheKey, 1800, function() use ($company, $dateFrom, $dateTo, $filters) {
            // Get all workflow chains for company documents
            $workflowChains = WorkflowChain::whereHas('document', function($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->with(['document', 'creator', 'documentWorkflows.recipient'])
            ->get();
            
            return [
                'period' => [
                    'from' => $dateFrom->toDateString(),
                    'to' => $dateTo->toDateString(),
                ],
                'overview' => $this->getCompanyOverview($workflowChains),
                'efficiency' => $this->calculateCompanyEfficiency($workflowChains),
                'bottlenecks' => $this->identifyCompanyBottlenecks($workflowChains),
                'department_performance' => $this->analyzeDepartmentPerformance($company, $dateFrom, $dateTo),
                'template_usage' => $this->getTemplateUsageAnalytics($company, $dateFrom, $dateTo),
                'trends' => $this->getCompanyWorkflowTrends($company, $dateFrom, $dateTo),
                'recommendations' => $this->generateRecommendations($workflowChains),
            ];
        });
    }
    
    /**
     * Get real-time workflow dashboard data
     *
     * @param User $user
     * @return array
     */
    public function getDashboardData(User $user): array
    {
        $cacheKey = "dashboard_data_{$user->id}";
        
        return Cache::remember($cacheKey, 300, function() use ($user) { // 5 minute cache
            // Current pending workflows for user
            $pendingWorkflows = DocumentWorkflow::where('recipient_id', $user->id)
                ->where('is_current_step', true)
                ->where('status', 'pending')
                ->with(['document', 'sender'])
                ->orderBy('due_date')
                ->get();
            
            // Workflows sent by user that are still active
            $sentActiveWorkflows = WorkflowChain::where('created_by', $user->id)
                ->where('status', 'active')
                ->with(['document', 'documentWorkflows' => function($query) {
                    $query->where('is_current_step', true)->with('recipient');
                }])
                ->get();
            
            // Recent completed workflows
            $recentCompleted = DocumentWorkflow::where('recipient_id', $user->id)
                ->whereIn('status', ['approved', 'forwarded', 'completed'])
                ->where('updated_at', '>=', now()->subDays(7))
                ->with(['document', 'sender'])
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get();
            
            return [
                'pending_count' => $pendingWorkflows->count(),
                'overdue_count' => $pendingWorkflows->where('due_date', '<', now()->toDateString())->count(),
                'urgent_count' => $pendingWorkflows->where('urgency', 'critical')->count(),
                'sent_active_count' => $sentActiveWorkflows->count(),
                'recent_completed_count' => $recentCompleted->count(),
                'pending_workflows' => $pendingWorkflows->map(function($workflow) {
                    return [
                        'id' => $workflow->id,
                        'document_title' => $workflow->document->title,
                        'tracking_number' => $workflow->tracking_number,
                        'sender' => $workflow->sender->name,
                        'due_date' => $workflow->due_date,
                        'urgency' => $workflow->urgency,
                        'purpose' => $workflow->purpose,
                        'days_until_due' => Carbon::parse($workflow->due_date)->diffInDays(now(), false),
                        'is_overdue' => $workflow->due_date < now()->toDateString(),
                    ];
                })->toArray(),
                'sent_workflows' => $sentActiveWorkflows->map(function($chain) {
                    $currentStep = $chain->documentWorkflows->first();
                    return [
                        'chain_id' => $chain->id,
                        'document_title' => $chain->document->title,
                        'current_recipient' => $currentStep ? $currentStep->recipient->name : 'N/A',
                        'progress' => ($chain->current_step / $chain->total_steps) * 100,
                        'started_at' => $chain->started_at->toDateString(),
                        'status' => $chain->status,
                    ];
                })->toArray(),
                'performance_summary' => $this->getPerformanceSummary($user),
            ];
        });
    }
    
    /**
     * Track workflow step timing
     *
     * @param DocumentWorkflow $workflow
     * @param string $action
     * @return void
     */
    public function trackStepTiming(DocumentWorkflow $workflow, string $action): void
    {
        $timingData = [
            'workflow_id' => $workflow->id,
            'user_id' => $workflow->recipient_id,
            'document_id' => $workflow->document_id,
            'step_order' => $workflow->step_order,
            'workflow_type' => $workflow->workflow_type,
            'action' => $action,
            'started_at' => $workflow->created_at,
            'completed_at' => now(),
            'duration_hours' => now()->diffInHours($workflow->created_at),
            'due_date' => $workflow->due_date,
            'was_overdue' => now() > Carbon::parse($workflow->due_date),
            'urgency' => $workflow->urgency,
        ];
        
        // Store timing data for analytics
        DB::table('workflow_step_timings')->insert($timingData);
        
        // Update running averages
        $this->updatePerformanceMetrics($workflow->recipient_id, $timingData);
    }
    
    // Private helper methods
    
    private function getChainProgress(WorkflowChain $chain): array
    {
        $workflows = $chain->documentWorkflows;
        $completedSteps = $workflows->whereIn('status', ['approved', 'forwarded', 'completed'])->count();
        
        $chainProgress = [
            'chain_id' => $chain->id,
            'workflow_type' => $chain->workflow_type,
            'status' => $chain->status,
            'current_step' => $chain->current_step,
            'total_steps' => $chain->total_steps,
            'completed_steps' => $completedSteps,
            'progress_percentage' => ($completedSteps / $chain->total_steps) * 100,
            'started_at' => $chain->started_at,
            'completed_at' => $chain->completed_at,
            'estimated_completion' => $this->estimateCompletionTime($chain),
            'steps' => [],
            'timeline' => [],
        ];
        
        foreach ($workflows as $workflow) {
            $step = [
                'workflow_id' => $workflow->id,
                'step_order' => $workflow->step_order,
                'recipient' => $workflow->recipient->name,
                'office' => $workflow->recipientOffice->name ?? null,
                'status' => $workflow->status,
                'is_current' => $workflow->is_current_step,
                'due_date' => $workflow->due_date,
                'completed_at' => $workflow->received_at,
                'urgency' => $workflow->urgency,
                'purpose' => $workflow->purpose,
            ];
            
            $chainProgress['steps'][] = $step;
            
            // Add to timeline if there's activity
            if ($workflow->received_at) {
                $chainProgress['timeline'][] = [
                    'timestamp' => $workflow->received_at,
                    'event' => "Step {$workflow->step_order} {$workflow->status}",
                    'user' => $workflow->recipient->name,
                    'action' => $workflow->status,
                ];
            }
        }
        
        return $chainProgress;
    }
    
    private function identifyBottlenecks($workflowChains): array
    {
        $bottlenecks = [];
        
        foreach ($workflowChains as $chain) {
            $workflows = $chain->documentWorkflows;
            
            foreach ($workflows as $workflow) {
                if ($workflow->is_current_step && $workflow->status === 'pending') {
                    $daysPending = now()->diffInDays($workflow->created_at);
                    
                    if ($daysPending > 3) { // Consider 3+ days as potential bottleneck
                        $bottlenecks[] = [
                            'workflow_id' => $workflow->id,
                            'recipient' => $workflow->recipient->name,
                            'office' => $workflow->recipientOffice->name ?? null,
                            'days_pending' => $daysPending,
                            'urgency' => $workflow->urgency,
                            'step_order' => $workflow->step_order,
                            'document_title' => $workflow->document->title,
                        ];
                    }
                }
            }
        }
        
        // Sort by days pending (worst first)
        usort($bottlenecks, function($a, $b) {
            return $b['days_pending'] <=> $a['days_pending'];
        });
        
        return $bottlenecks;
    }
    
    private function analyzeReceivedWorkflows($workflows): array
    {
        return [
            'total' => $workflows->count(),
            'completed' => $workflows->whereIn('status', ['approved', 'forwarded', 'completed'])->count(),
            'pending' => $workflows->where('status', 'pending')->count(),
            'overdue' => $workflows->where('due_date', '<', now()->toDateString())->count(),
            'by_urgency' => [
                'critical' => $workflows->where('urgency', 'critical')->count(),
                'high' => $workflows->where('urgency', 'high')->count(),
                'medium' => $workflows->where('urgency', 'medium')->count(),
                'low' => $workflows->where('urgency', 'low')->count(),
            ],
            'avg_completion_time' => $this->calculateAverageCompletionTime($workflows),
        ];
    }
    
    private function analyzeSentWorkflows($workflows): array
    {
        return [
            'total' => $workflows->count(),
            'completed' => $workflows->whereIn('status', ['approved', 'forwarded', 'completed'])->count(),
            'pending' => $workflows->where('status', 'pending')->count(),
            'unique_recipients' => $workflows->pluck('recipient_id')->unique()->count(),
            'by_urgency' => [
                'critical' => $workflows->where('urgency', 'critical')->count(),
                'high' => $workflows->where('urgency', 'high')->count(),
                'medium' => $workflows->where('urgency', 'medium')->count(),
                'low' => $workflows->where('urgency', 'low')->count(),
            ],
        ];
    }
    
    private function calculateUserPerformance($workflows): array
    {
        $completed = $workflows->whereIn('status', ['approved', 'forwarded', 'completed']);
        $totalProcessingTime = 0;
        $onTimeCount = 0;
        
        foreach ($completed as $workflow) {
            if ($workflow->received_at) {
                $processingTime = Carbon::parse($workflow->received_at)->diffInHours($workflow->created_at);
                $totalProcessingTime += $processingTime;
                
                if ($workflow->received_at <= $workflow->due_date) {
                    $onTimeCount++;
                }
            }
        }
        
        return [
            'completion_rate' => $workflows->count() > 0 ? ($completed->count() / $workflows->count()) * 100 : 0,
            'on_time_rate' => $completed->count() > 0 ? ($onTimeCount / $completed->count()) * 100 : 0,
            'avg_processing_hours' => $completed->count() > 0 ? $totalProcessingTime / $completed->count() : 0,
            'total_completed' => $completed->count(),
        ];
    }
    
    private function calculateAverageCompletionTime($workflows): float
    {
        $completed = $workflows->whereIn('status', ['approved', 'forwarded', 'completed'])->where('received_at', '!=', null);
        
        if ($completed->isEmpty()) {
            return 0;
        }
        
        $totalHours = $completed->sum(function($workflow) {
            return Carbon::parse($workflow->received_at)->diffInHours($workflow->created_at);
        });
        
        return $totalHours / $completed->count();
    }
    
    private function estimateCompletionTime(WorkflowChain $chain): ?Carbon
    {
        if ($chain->status === 'completed') {
            return null;
        }
        
        // Simple estimation based on remaining steps and average completion times
        $remainingSteps = $chain->total_steps - $chain->current_step + 1;
        $avgStepTime = 24; // hours - this could be more sophisticated
        
        return now()->addHours($remainingSteps * $avgStepTime);
    }
    
    private function getPerformanceSummary(User $user): array
    {
        $last30Days = now()->subDays(30);
        
        $recentWorkflows = DocumentWorkflow::where('recipient_id', $user->id)
            ->where('created_at', '>=', $last30Days)
            ->get();
        
        $completed = $recentWorkflows->whereIn('status', ['approved', 'forwarded', 'completed']);
        
        return [
            'total_processed' => $completed->count(),
            'avg_processing_time' => $this->calculateAverageCompletionTime($recentWorkflows),
            'on_time_percentage' => $completed->count() > 0 ? 
                ($completed->filter(function($w) { 
                    return $w->received_at && $w->received_at <= $w->due_date; 
                })->count() / $completed->count()) * 100 : 0,
            'pending_count' => $recentWorkflows->where('status', 'pending')->count(),
        ];
    }
    
    private function updatePerformanceMetrics(int $userId, array $timingData): void
    {
        // Update user performance cache or database records
        $cacheKey = "user_performance_{$userId}";
        Cache::forget($cacheKey);
    }
    
    private function calculateProductivityMetrics(User $user, Carbon $dateFrom, Carbon $dateTo): array
    {
        // Implement productivity calculations
        return [
            'workflows_per_day' => 0,
            'peak_hours' => [],
            'efficiency_score' => 0,
        ];
    }
    
    private function getWorkflowTrends(User $user, Carbon $dateFrom, Carbon $dateTo): array
    {
        // Implement trend analysis
        return [
            'completion_trend' => 'stable',
            'workload_trend' => 'increasing',
            'performance_trend' => 'improving',
        ];
    }
    
    private function getCompanyOverview($workflowChains): array
    {
        return [
            'total_workflows' => $workflowChains->count(),
            'active_workflows' => $workflowChains->where('status', 'active')->count(),
            'completed_workflows' => $workflowChains->where('status', 'completed')->count(),
            'avg_completion_time' => 0, // Calculate based on completed workflows
            'success_rate' => 0, // Calculate success rate
        ];
    }
    
    private function calculateCompanyEfficiency($workflowChains): array
    {
        // Implement company efficiency calculations
        return [
            'overall_efficiency' => 85.5,
            'bottleneck_departments' => [],
            'fastest_departments' => [],
        ];
    }
    
    private function identifyCompanyBottlenecks($workflowChains): array
    {
        // Implement company bottleneck analysis
        return [];
    }
    
    private function analyzeDepartmentPerformance(Company $company, Carbon $dateFrom, Carbon $dateTo): array
    {
        // Implement department performance analysis
        return [];
    }
    
    private function getTemplateUsageAnalytics(Company $company, Carbon $dateFrom, Carbon $dateTo): array
    {
        // Implement template usage analytics
        return [];
    }
    
    private function getCompanyWorkflowTrends(Company $company, Carbon $dateFrom, Carbon $dateTo): array
    {
        // Implement company trend analysis
        return [];
    }
    
    private function generateRecommendations($workflowChains): array
    {
        // Implement recommendation engine
        return [];
    }
}
