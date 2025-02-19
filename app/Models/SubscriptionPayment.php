<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    protected $fillable = [
        'company_subscription_id',
        'amount',
        'payment_method',
        'status',
        'transaction_reference',
        'payment_date',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function subscription()
    {
        return $this->belongsTo(CompanySubscription::class, 'company_subscription_id');
    }
}

