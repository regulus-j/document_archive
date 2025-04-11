<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CompanyAccount;
use App\Models\Office;

class OfficeCompanyUser extends Seeder
{
    public function run()
    {
        // Create company accounts first
        $companies = CompanyAccount::factory()->count(2)->create();
        
        // Create offices for each company
        $offices = [];
        foreach ($companies as $company) {
            $companyOffices = Office::factory()
                ->count(5)
                ->create(['company_id' => $company->id]);
            $offices = array_merge($offices, $companyOffices->toArray());
        }
        
        // Create users explicitly first
        $users = User::factory()->count(11)->create();
        
        // Now assign offices to each user
        foreach ($users as $user) {
            // Determine how many offices to assign to this user (1-3)
            $numOffices = rand(1, 3);
            
            // Get random office IDs
            $officeIds = array_column($offices, 'id');
            shuffle($officeIds);
            $selectedOfficeIds = array_slice($officeIds, 0, $numOffices);
            
            // Assign offices to user
            $user->offices()->attach($selectedOfficeIds);
            
            // Assign user to a company
            $companyId = $offices[array_search($selectedOfficeIds[0], array_column($offices, 'id'))]['company_id'];
            \App\Models\CompanyUser::create([
                'user_id' => $user->id,
                'company_id' => $companyId
            ]);
        }
    }
}