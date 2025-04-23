<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use App\Models\CompanyAccount;
use App\Models\CompanyUser;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = [
            [
                'email' => 'superadmin@example.com',
                'first_name' => 'SuperAdmin',
                'last_name' => 'User',
                'password' => Hash::make('password')
            ],
            [
                'email' => 'admin@example.com',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('password')
            ],
            [
                'email' => 'user@example.com',
                'first_name' => 'Regular',
                'last_name' => 'User',
                'password' => Hash::make('password')
            ],
        ];

        $adminUserId = null;
        $regularUserId = null;

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Assign roles based on email
            if ($userData['email'] === 'superadmin@example.com') {
                $role = Role::firstOrCreate(['name' => 'super-admin']);
                $user->assignRole('super-admin');
                // Super admin is NOT assigned to any company
            } elseif ($userData['email'] === 'admin@example.com') {
                $role = Role::firstOrCreate(['name' => 'company-admin']);
                $user->assignRole('company-admin');
                
                // Save admin user ID to create company later
                $adminUserId = $user->id;
            } else if ($userData['email'] === 'user@example.com') {
                $role = Role::firstOrCreate(['name' => 'user']);
                $user->assignRole('user');
                
                // Save regular user ID to assign to the same company as admin
                $regularUserId = $user->id;
            } else {
                $role = Role::firstOrCreate(['name' => 'user']);
                $user->assignRole('user');
            }
        }
        
        // Now create the company with the actual admin user ID
        if ($adminUserId) {
            // Create company AFTER we have the admin user
            $company = CompanyAccount::firstOrCreate(
                ['id' => 1],
                [
                    'user_id' => $adminUserId, // Use the actual admin ID
                    'company_name' => 'Demo Company',
                    'registered_name' => 'Demo Company Ltd',
                    'company_email' => 'info@democompany.com',
                    'company_phone' => '123-456-7890',
                    'industry' => 'Technology',
                    'company_size' => 'Medium'
                ]
            );
            
            // Attach admin to company
            $this->attachUserToCompany($adminUserId, $company->id);
            
            // Attach regular user to company if it exists
            if ($regularUserId) {
                $this->attachUserToCompany($regularUserId, $company->id);
            }
        }
    }
    
    /**
     * Attach a user to a company
     */
    private function attachUserToCompany($userId, $companyId)
    {
        // Check if relationship already exists
        $exists = CompanyUser::where('user_id', $userId)
            ->where('company_id', $companyId)
            ->exists();
            
        if (!$exists) {
            CompanyUser::create([
                'user_id' => $userId,
                'company_id' => $companyId
            ]);
        }
    }
}