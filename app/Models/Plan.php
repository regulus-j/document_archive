<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'plan_name',
        'description',
        'price',
        'billing_cycle',
        'is_active',
        'feature_1',
        'feature_2',
        'feature_3',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'feature_1' => 'boolean',
        'feature_2' => 'boolean',
        'feature_3' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(CompanySubscription::class);
    }
}

