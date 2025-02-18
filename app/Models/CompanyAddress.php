<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyAddress extends Model
{
    protected $fillable = [
        'company_id',
        'address',
        'city',
        'state',
        'country',
        'zip_code'
    ];

    public function company()
    {
        return $this->belongsTo(CompanyAccount::class, 'company_id');
    }
}
