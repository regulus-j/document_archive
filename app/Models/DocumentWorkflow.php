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
        $this->save();
    }

    public function approve()
    {
        $this->status = 'approved';
        $this->save();
    }

    public function reject()
    {
        $this->status = 'rejected';
        $this->save();
    }
    
    public function return()
    {
        $this->status = 'returned';
        $this->save();
    }
    
    public function refer()
    {
        $this->status = 'referred';
        $this->save();
    }
    
    public function forward()
    {
        $this->status = 'forwarded';
        $this->save();
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
}
