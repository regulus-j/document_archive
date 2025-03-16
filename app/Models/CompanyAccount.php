<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CompanyAccount extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'registered_name',
        'company_email',
        'company_phone',
        'industry',
        'company_size',
    ];

    /**
     * Get the user who owns this company.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
{
    return $this->belongsTo(User::class, 'admin_id');
}


    /**
     * Get the address associated with the company.
     */
    public function address(): HasOne
    {
        return $this->hasOne(CompanyAddress::class, 'company_id');
    }

    /**
     * Get all offices associated with the company.
     */
    public function offices(): HasMany
    {
        return $this->hasMany(Office::class, 'company_id');
    }

    /**
     * Get all employees associated with the company.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(User::class, 'company_id')->where('role', 'employee');
    }

    /**
     * Get all managers associated with the company.
     */
    public function managers(): HasMany
    {
        return $this->hasMany(User::class, 'company_id')->where('role', 'manager');
    }

    /**
     * Get all subscriptions related to the company.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(CompanySubscription::class, 'company_id');
    }

    /**
     * Get all users assigned to the company (if using a pivot table).
     */

    public function users(): HasMany
{
    return $this->hasMany(User::class, 'company_id');
}




}
