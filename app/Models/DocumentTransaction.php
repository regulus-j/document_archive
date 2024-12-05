<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTransaction extends Model
{
    protected $table = 'document_transaction';
    
    protected $fillable = [
        'doc_id',
        'from_office',
        'to_office'
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'doc_id');
    }

    public function fromOffice()
    {
        return $this->belongsTo(Office::class, 'from_office');
    }
    
    public function toOffice()
    {
        return $this->belongsTo(Office::class, 'to_office');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
