<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'company_id',
        'created_by',
        'workflow_type',
        'steps_config',
        'is_active',
        'is_public',
        'usage_count',
    ];

    protected $casts = [
        'steps_config' => 'array',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(\App\Models\CompanyAccount::class, 'company_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where(function ($q) use ($companyId) {
            $q->where('company_id', $companyId)
              ->orWhere('is_public', true);
        });
    }

    public function scopeSequential($query)
    {
        return $query->where('workflow_type', 'sequential');
    }

    public function scopeParallel($query)
    {
        return $query->where('workflow_type', 'parallel');
    }

    // Helper methods
    public function getTotalSteps()
    {
        return count($this->steps_config ?? []);
    }

    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    public function getStepByOrder($stepOrder)
    {
        $steps = $this->steps_config ?? [];
        return collect($steps)->firstWhere('order', $stepOrder);
    }

    public function createWorkflowChain($documentId, $createdBy)
    {
        return WorkflowChain::create([
            'document_id' => $documentId,
            'created_by' => $createdBy,
            'workflow_type' => $this->workflow_type,
            'current_step' => 1,
            'total_steps' => $this->getTotalSteps(),
            'status' => 'active',
            'description' => "Workflow based on template: {$this->name}",
            'step_config' => $this->steps_config,
            'started_at' => now(),
        ]);
    }
}
