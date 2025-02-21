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
        'details',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function logDocumentAction($documentId, $userId, $action, $status, $details = null)
    {
        return self::create([
            'document_id' => $documentId,
            'user_id'     => $userId,
            'action'      => $action,
            'status'      => $status,
            'details'     => $details,
        ]);
    }
}
