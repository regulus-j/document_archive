<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CompanyAccount extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'company_name',
        'registered_name',
        'company_email',
        'company_phone',
        'industry',
        'company_size',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employees()
    {
        return $this->belongsToMany(User::class, 'company_users', 'company_id', 'user_id');
    }


    public function offices()
    {
        return $this->hasMany(Office::class, 'company_id');
    }

    //company address
    public function address(): HasMany
    {
        return $this->hasMany(CompanyAddress::class, 'company_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(CompanySubscription::class, 'company_id');
    }
}

