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
        // Create the admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'middle_name' => 'AA',
                'last_name' => 'User',
                'password' => Hash::make('password') // Ensure this is hashed
            ]
        );

        // Check if the 'super-admin' role already exists
        $role = Role::firstOrCreate(['name' => 'super-admin']);

        $admin = Admin::firstOrCreate([
            'user_id' => $user->id,
        ]);

        // Assign the 'super-admin' role to the user
        $user->assignRole($role);

        // Define allowed permissions for super-admin
        $allowedPermissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'document-list',
            'document-create',
            'document-edit',
            'document-delete',
            'document-release',
            'document-receive',
            'audit-list',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete'
        ];

        // Get only the allowed permissions
        $permissions = Permission::whereIn('name', $allowedPermissions)->get();
        
        // Sync only the allowed permissions to the super-admin role
        $role->syncPermissions($permissions);
    }
}