<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
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
        // Create the superadmin user
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'middle_name' => 'AA',
                'last_name' => 'User',
                'password' => Hash::make('password') // Ensure this is hashed
            ]
        );

        // Create the superadmin role if it doesn't exist
        $role = Role::firstOrCreate(['name' => 'superadmin']);

        // Assign the superadmin role to the user
        $user->assignRole($role);
    }
}