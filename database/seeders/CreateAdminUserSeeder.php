<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
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
        // Create the admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'middle_name' => '',
                'last_name' => 'User',
                'password' => Hash::make('password') // Ensure this is hashed
            ]
        );

        // Check if the 'Admin' role already exists
        $role = Role::firstOrCreate(['name' => 'Admin']);

        // Assign the 'Admin' role to the user
        $user->assignRole($role);

        // Optionally, add permissions to the 'Admin' role
        $permissions = Permission::all();
        $role->syncPermissions($permissions);
    }
}