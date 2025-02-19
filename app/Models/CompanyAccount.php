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
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //users under the company
    public function employees()
    {
        return $this->belongsToMany(User::class, 'company_users', 'company_id', 'user_id');
    }

    public function offices()
    {
        return $this->hasMany(Office::class, 'company_id');
    }

    //company address
    public function address()
    {
        return $this->hasOne(CompanyAddress::class, 'company_id');
    }

    //company subscriptions
    public function subscriptions()
    {
        return $this->hasMany(CompanySubscription::class, 'company_id');
    }
}
