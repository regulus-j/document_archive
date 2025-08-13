<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_id',
        'filename',
        'path',
        'route_id',
        'storage_size',
        'mime_type',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}