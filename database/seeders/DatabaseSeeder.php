<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Define roles
        $roles = ['superadmin', 'admin', 'user'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Define permissions
        $permissions = [
            'manage users',
            'manage documents',
            'view dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        Role::where('name', 'superadmin')->first()->givePermissionTo(Permission::all());
        Role::where('name', 'admin')->first()->givePermissionTo(['manage users', 'view dashboard']);
        Role::where('name', 'user')->first()->givePermissionTo(['view dashboard']);

        // Create a Super Admin user
        if (!User::where('email', 'superadmin@example.com')->exists()) {
            $superAdmin = User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'password' => bcrypt('password123'),
            ]);
            $superAdmin->assignRole('superadmin');
        }
    }
}
