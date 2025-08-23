<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentWorkflow extends Model
{
    //
    protected $table = 'document_workflows';

    protected $fillable = [
        'tracking_number',
        'document_id',
        'sender_id',
        'recipient_id',
        'recipient_office',
        'step_order',
        'status',
        'purpose',
        'urgency',
        'due_date',
        'remarks',
        'is_paused',
    ];

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

    public function recipientOffice()
    {
        return $this->belongsTo(Office::class, 'recipient_office');
    }

    public function receive()
    {
        $this->status = 'received';
        $this->received_at = now();
        $this->save();
        
        // Sync document status
        $this->syncDocumentStatus();
    }

    public function approve()
    {
        $this->status = 'approved';
        $this->save();
        
        // Sync document status
        $this->syncDocumentStatus();
    }

    public function reject()
    {
        $this->status = 'rejected';
        $this->save();
        
        // Sync document status
        $this->syncDocumentStatus();
    }
    
    public function return()
    {
        $this->status = 'returned';
        $this->save();
        
        // Sync document status
        $this->syncDocumentStatus();
    }
    
    public function refer()
    {
        $this->status = 'referred';
        $this->save();
        
        // Sync document status
        $this->syncDocumentStatus();
    }
    
    public function forward()
    {
        $this->status = 'forwarded';
        $this->save();
        
        // Sync document status
        $this->syncDocumentStatus();
    }

    /**
     * Synchronize document status based on workflow states
     */
    private function syncDocumentStatus()
    {
        $document = $this->document;
        if (!$document || !$document->status) {
            return;
        }

        $allWorkflows = $document->documentWorkflow;
        
        // If no workflows exist, keep current status
        if ($allWorkflows->isEmpty()) {
            return;
        }

        // Determine overall document status based on workflow states
        $statuses = $allWorkflows->pluck('status')->unique();
        
        if ($statuses->contains('rejected')) {
            $document->status()->update(['status' => 'rejected']);
        } elseif ($statuses->contains('returned')) {
            $document->status()->update(['status' => 'returned']);
        } elseif ($allWorkflows->every(fn($w) => $w->status === 'received')) {
            $document->status()->update(['status' => 'received']);
        } elseif ($allWorkflows->every(fn($w) => in_array($w->status, ['approved', 'received']))) {
            $document->status()->update(['status' => 'complete']);
        } elseif ($statuses->contains('pending')) {
            $document->status()->update(['status' => 'forwarded']);
        }
    }

    public function changeStatus($action)
    {
        $this->status = $action;
        $this->save();
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isReceived() 
    {
        return $this->status === 'received';
    }
    
    public function isApproved()
    {
        return $this->status === 'approved';
    }
    
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
    
    public function isReturned()
    {
        return $this->status === 'returned';
    }
    
    public function isReferred()
    {
        return $this->status === 'referred';
    }
    
    public function isForwarded()
    {
        return $this->status === 'forwarded';
    }
    
    public function canProcess()
    {
        return $this->status === 'received';
    }
    
    public function canReceive()
    {
        return $this->status === 'pending';
    }
    
    public function workflowActive()
    {
        return !in_array($this->status, ['rejected', 'returned', 'approved']);
    }
    public function isOverdue()
    {
        if (!$this->due_date) {
            return false;
        }
        
        return now()->startOfDay()->gt($this->due_date);
    }
    
    public function getDaysRemainingAttribute()
    {
        if (!$this->due_date) {
            return null;
        }
        
        return now()->startOfDay()->diffInDays($this->due_date, false);
    }
    
    public function pause()
    {
        $this->is_paused = true;
        $this->save();
    }

    public function resume()
    {
        $this->is_paused = false;
        $this->save();
    }

    public function isPaused()
    {
        return $this->is_paused === true;
    }
}
