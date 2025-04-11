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

        // Create users first
        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            if ($userData['email'] === 'superadmin@example.com') {
                $role = Role::firstOrCreate(['name' => 'super-admin']);
                $user->assignRole('super-admin');
            } elseif ($userData['email'] === 'admin@example.com') {
                $role = Role::firstOrCreate(['name' => 'company-admin']);
                $user->assignRole('company-admin');
                $adminUserId = $user->id;
            } else {
                $role = Role::firstOrCreate(['name' => 'user']);
                $user->assignRole('user');
            }
        }

        // Then create company
        $company = CompanyAccount::firstOrCreate(
            ['id' => 1],
            [
                'user_id' => $adminUserId,
                'company_name' => 'Demo Company',
                'registered_name' => 'Demo Company Ltd',
                'company_email' => 'info@democompany.com',
                'company_phone' => '123-456-7890',
                'industry' => 'Technology',
                'company_size' => 'Medium'
            ]
        );

        // Finally, attach users to company (except super-admin)
        foreach ($users as $userData) {
            if ($userData['email'] !== 'superadmin@example.com') {
                $user = User::where('email', $userData['email'])->first();
                $this->attachUserToCompany($user->id, $company->id);
            }
        }
    }

    private function attachUserToCompany($userId, $companyId)
    {
        CompanyUser::firstOrCreate([
            'user_id' => $userId,
            'company_id' => $companyId
        ]);
    }
}
