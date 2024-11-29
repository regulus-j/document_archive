<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    //
    protected $fillable = [
        "name",
    ];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function folders()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }
}
