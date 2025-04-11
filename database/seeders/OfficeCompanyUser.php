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
        // First check if we have users, if not create some
        if (User::count() == 0) {
            User::factory()->count(5)->create();
        }
        
        // Get existing users to use for companies
        $users = User::all();
        
        // Create company accounts with valid user_id references
        $companies = [];
        foreach ($users->take(2) as $index => $user) {
            $company = CompanyAccount::factory()->create([
                'user_id' => $user->id
            ]);
            $companies[] = $company;
        }
        
        // If no companies were created, stop here
        if (empty($companies)) {
            return;
        }
        
        // Create offices for each company
        $offices = [];
        foreach ($companies as $company) {
            $companyOffices = Office::factory()
                ->count(5)
                ->create(['company_id' => $company->id]);
            $offices = array_merge($offices, $companyOffices->toArray());
        }
        
        // Create more users and assign each to at least one office
        User::factory()->count(11)->create()->each(function ($user) use ($offices) {
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
        });
    }
}