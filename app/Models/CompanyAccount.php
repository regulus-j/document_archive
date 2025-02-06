<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyAccount extends Model
{
    //

    protected $fillable = [
        'user_id',
        'company_name',
        'registered_name',
        'company_email',
        'company_phone',
    ];

    //owner of the company
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //users under the company
    public function employees()
    {
        return $this->hasMany(User::class, 'company_id');
    }

    //company addresses
    public function addresses()
    {
        return $this->hasMany(CompanyAddress::class, 'company_id');
    }

    //company subscriptions
    public function subscriptions()
    {
        return $this->hasMany(CompanySubscription::class, 'company_id');
    }
}
