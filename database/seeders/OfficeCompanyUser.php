<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CompanyAccount;
use App\Models\Office;

class OfficeCompanyUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create a single user
        User::factory()->create();
    
        // Create 10 users
        User::factory()->count(10)->create();
        
        // Create a company account for each user
        CompanyAccount::factory()->count(10)->create();
        
        // Create offices
        Office::factory()->count(10)->create();
    }
}
