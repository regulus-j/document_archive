<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentCategories extends Model
{
    protected $table = 'document_categories';
    //
    protected $fillable = [
        'name',
    ];
}
