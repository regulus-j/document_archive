<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'filename',
        'path',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
