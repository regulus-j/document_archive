<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    protected $table = 'document_categories';
    
    protected $fillable = [
        'category'
    ];

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_category', 'category_id', 'doc_id');
    }
}
