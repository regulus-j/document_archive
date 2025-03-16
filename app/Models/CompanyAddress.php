<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyAddress extends Model
{
    protected $fillable = [
        'company_id',
        'street',
        'city',
        'state',
        'zip_code',
        'country',
    ];

    // Define the inverse relationship
    public function companyAccount()
    {
        return $this->belongsTo(CompanyAccount::class);
    }
}
