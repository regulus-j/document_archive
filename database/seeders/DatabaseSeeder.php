<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\CompanyAccount;
use App\Models\Office;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Assign company owners the admin role
        $companyOwners = User::whereHas('company')->get();
        foreach ($companyOwners as $owner) {
            // Skip users who are already super-admins
            if (!$owner->hasRole('super-admin')) {
                $owner->assignRole('company-admin');
            }
        }

        // Assign the basic user role to all remaining users
        $regularUsers = User::whereDoesntHave('roles')->get();
        foreach ($regularUsers as $user) {
            $user->assignRole('user');
        }
    }
}
