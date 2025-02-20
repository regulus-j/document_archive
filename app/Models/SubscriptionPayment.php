<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionPayment extends Model
{
    // Add the correct table name:
    protected $table = 'subscription_payments';

    protected $fillable = [
        'company_subscription_id',
        'payment_date',
        'amount',
        'payment_method',
        'status',
        'transaction_reference',
        'notes',
    ];

    // Example relationship: a payment belongs to a subscription
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(CompanySubscription::class, 'company_subscription_id');
    }
}

