<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentAudit extends Model
{
    protected $table = 'document_audit_logs';
    protected $fillable = [
        'document_id',
        'user_id',
        'action',
        'status',
        'details'
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
