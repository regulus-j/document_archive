<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyAddress extends Model
{
    protected $fillable = [
        'company_id',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyAccount::class);
    }
}