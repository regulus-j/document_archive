<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class WorkflowChain extends Model
{
    use HasUuids;
    
    protected $fillable = [
        'document_id',
        'created_by',
        'workflow_type',
        'current_step',
        'total_steps',
        'status',
        'description',
        'step_config',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'step_config' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function workflows()
    {
        return $this->hasMany(DocumentWorkflow::class, 'workflow_chain_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
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
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function getProgressPercentage()
    {
        if ($this->total_steps <= 0) {
            return 0;
        }
        return round(($this->current_step - 1) / $this->total_steps * 100);
    }

    public function getCurrentStepWorkflows()
    {
        return $this->workflows()
            ->where('is_current_step', true)
            ->with('recipient');
    }

    public function getNextStepWorkflows()
    {
        return $this->workflows()
            ->where('step_order', $this->current_step + 1)
            ->with('recipient');
    }
}
