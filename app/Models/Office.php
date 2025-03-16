<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Office extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'name',
        'parent_office_id',
    ];

    /**
     * Get the child offices for this office.
     */
    public function childOffices()
    {
        return $this->hasMany(Office::class, 'parent_office_id');
    }

    /**
     * Get the parent office of this office.
     */
    public function parentOffice()
    {
        return $this->belongsTo(Office::class, 'parent_office_id');
    }

    /**
     * Get the users associated with this office.
     */
  
    public function users()
{
    return $this->hasMany(User::class, 'office_id');
}


    /**
     * Get all document transactions sent from this office.
     */
    public function sentTransactions()
    {
        return $this->hasMany(DocumentTransaction::class, 'from_office');
    }

    /**
     * Get all document transactions received by this office.
     */
    public function receivedTransactions()
    {
        return $this->hasMany(DocumentTransaction::class, 'to_office');
    }

    /**
     * Get the company that owns the office.
     */
    public function company()
    {
        return $this->belongsTo(CompanyAccount::class, 'company_id');
    }
}
