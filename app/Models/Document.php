<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Folder;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'uploader',
        'description',
        'content',
        'path',
        'master',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uploader');
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function masterDocument()
    {
        return $this->belongsTo(Document::class, 'master');
    }

    public function childDocuments()
    {
        return $this->hasMany(Document::class, 'master');
    }
}
