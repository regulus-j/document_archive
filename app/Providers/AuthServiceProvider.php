<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\CompanyAccount;
use App\Policies\CompanyPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        CompanyAccount::class => CompanyPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
