<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTrackingNumber extends Model
{
    protected $table = 'document_trackingnumbers';

    protected $fillable = [
        'doc_id',
        'tracking_number',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'doc_id');
    }
}
