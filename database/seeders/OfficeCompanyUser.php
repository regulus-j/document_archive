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
        });  // Fixed: Added missing closing parenthesis and semicolon
        
        // Find admin and regular user by email
        $adminUser = User::where('email', 'admin@example.com')->first();
        $regularUser = User::where('email', 'user@example.com')->first();
        
        // If the users exist, assign them to random offices
        if ($adminUser && !empty($offices)) {
            // Get random office IDs
            $officeIds = array_column($offices, 'id');
            shuffle($officeIds);
            $selectedOfficeId = $officeIds[0]; // Select one random office
            
            // Assign admin to the office
            $adminUser->offices()->attach($selectedOfficeId);
            
            // Get the company for this office
            $officeIndex = array_search($selectedOfficeId, array_column($offices, 'id'));
            $companyId = $offices[$officeIndex]['company_id'];
            
            // Make sure user is connected to the company (if not already)
            if (!\App\Models\CompanyUser::where('user_id', $adminUser->id)->where('company_id', $companyId)->exists()) {
                \App\Models\CompanyUser::create([
                    'user_id' => $adminUser->id,
                    'company_id' => $companyId
                ]);
            }
        }
        
        if ($regularUser && !empty($offices)) {
            // Get random office IDs (different from admin's)
            $officeIds = array_column($offices, 'id');
            shuffle($officeIds);
            $selectedOfficeId = $officeIds[0]; // Select one random office
            
            // Assign regular user to the office
            $regularUser->offices()->attach($selectedOfficeId);
            
            // Get the company for this office
            $officeIndex = array_search($selectedOfficeId, array_column($offices, 'id'));
            $companyId = $offices[$officeIndex]['company_id'];
            
            // Make sure user is connected to the company (if not already)
            if (!\App\Models\CompanyUser::where('user_id', $regularUser->id)->where('company_id', $companyId)->exists()) {
                \App\Models\CompanyUser::create([
                    'user_id' => $regularUser->id,
                    'company_id' => $companyId
                ]);
            }
        }
    }
}