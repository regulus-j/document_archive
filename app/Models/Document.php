<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'uploader',
        'description',
        'content',
        'path',
        'remarks'
    ];
    
    protected $attributes = [
        'content' => null
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uploader');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id');

    }

    public function masterDocument()
    {
        return $this->belongsTo(Document::class, 'master');
    }

    public function childDocuments()
    {
        return $this->hasMany(Document::class, 'master');
    }

    public function transactions()
    {
        return $this->hasMany(DocumentTransaction::class, 'doc_id');
    }

    public function transaction()
    {
        return $this->hasOne(DocumentTransaction::class, 'doc_id');
    }

    public function categories()
    {
        return $this->belongsToMany(DocumentCategory::class, 'document_category', 'doc_id', 'category_id');
    }

    public function status()
    {
        return $this->hasOne(DocumentStatus::class, 'doc_id');
    }

    public function trackingNumber()

    {
        return $this->hasOne(DocumentTrackingNumber::class, 'doc_id');
    }

    public function attachments()
    {
        return $this->hasMany(DocumentAttachment::class);
    }
}
