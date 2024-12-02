<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $fillable = [
        'name',
        'parent_office_id'
    ];

    public function childOffices()
    {
        return $this->hasMany(Office::class, 'parent_office_id');
    }

    public function parentOffice()
    {
        return $this->belongsTo(Office::class, 'parent_office_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function sentTransactions()
    {
        return $this->hasMany(DocumentTransaction::class, 'from_office');
    }

    public function receivedTransactions()
    {
        return $this->hasMany(DocumentTransaction::class, 'to_office');
    }
}
