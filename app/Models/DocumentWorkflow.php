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
        'step_order',
        'status',
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

    public function changeStatus($action)
    {
        $this->status = $action;
        $this->save();
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }
}
