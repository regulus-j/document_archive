<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    //
    protected $fillable = [
        'company_id',
        'plan_id',
        'start_date',
        'end_date',
        'status',
        'auto_renew',
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
        return $this->hasMany(Payment::class, 'company_subscription_id');
    }
}
