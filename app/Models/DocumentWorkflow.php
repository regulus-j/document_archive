<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentWorkflow extends Model
{
    use SoftDeletes; // Enables soft deletes

    protected $table = 'document_workflows';

    protected $fillable = [
        'document_id',
        'sender_id',
        'recipient_id',
        'step_order',
        'status',
        'remarks',
    ];

    protected $attributes = [
        'status' => 'pending', // Default status
    ];

    /**
     * Relationships
     */
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Approve the workflow step
     */
    public function approve()
    {
        $this->update(['status' => 'approved']);
    }

    /**
     * Reject the workflow step
     */
    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }

    /**
     * Scope for pending workflows
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Check if workflow is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if workflow is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }
}
