<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'uploader',
        'company_id',
        'document_id',
        'description',
        'content',
        'path',
        'storage_size',
        'classification',
        'purpose',
        'category',
    ];

    protected $attributes = [
        'content' => null,
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
        return $this->hasOne(DocumentTrackingNumber::class, 'document_id');
    }

    public function attachments()
    {
        return $this->hasMany(DocumentAttachment::class);
    }

    public function documentWorkflow()
    {
        return $this->hasMany(DocumentWorkflow::class, 'document_id');
    }
     public function workflow()
    {
        return $this->hasOne(DocumentWorkflow::class, 'document_id');
    }

    public function originatingOffice()
    {
        return $this->belongsTo(Office::class, 'from_office'); // Assuming 'from_office' is the foreign key
    }

    public function recipients() {
        return $this->belongsToMany(User::class, 'document_recipients', 'document_id', 'recipient_id');
    }

    public function company()
    {
        return $this->belongsTo(CompanyAccount::class);
    }

    /**
     * Get the user who archived the document
     */
    public function archivedBy()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute()
    {
        if (!$this->storage_size) {
            return '0 KB';
        }
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $size = $this->storage_size;
        $factor = floor((strlen($size) - 1) / 3);
        
        return sprintf("%.2f %s", $size / pow(1024, $factor), $units[$factor]);
    }
    
    /**
     * Check if document is eligible for deletion
     */
    public function isEligibleForDeletion()
    {
        return !$this->is_archived;
    }
    
    /**
     * Scope a query to only include documents from a specific company
     */
    public function scopeCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
    
    /**
     * Scope a query to include only documents created before a specific date
     */
    public function scopeCreatedBefore($query, $date)
    {
        return $query->where('created_at', '<', $date);
    }
    
    /**
     * Scope a query to search documents by title, content and description
     */
    public function scopeSearch($query, $searchTerm)
    {
        if ($searchTerm) {
            return $query->where(function($query) use ($searchTerm) {
                $query->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('content', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }
        
        return $query;
    }

    public function allowedViewers()
    {
        return $this->hasMany(DocumentAllowedViewer::class, 'doc_id');
    }
}
