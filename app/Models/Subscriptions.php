<?php

namespace App\Models;
use App\Models\Subscription; 

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscriptions extends Model
{
    protected $table = 'company_subscriptions';

    protected $fillable = [
        'company_id',
        'plan_id',
        'start_date',
        'end_date',
        'status',
        'auto_renew',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'auto_renew' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(CompanyAccount::class, 'company_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function payments()
    {
        return $this->hasMany(SubscriptionPayment::class, 'company_subscription_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCanceled(): bool
    {
        return $this->status === 'canceled';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    public function activate(): void
    {
        $this->update(['status' => 'active']);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'canceled']);
    }

    public function expire(): void
    {
        $this->update(['status' => 'expired']);
    }
}