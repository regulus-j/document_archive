<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentStatus extends Model
{
    //
    protected $table = 'document_status';

    protected $fillable = [
        'status',
        'doc_id',
    ];

    public function documents()
    {
        return $this->belongsTo(Document::class, 'doc_id');
    }
}
