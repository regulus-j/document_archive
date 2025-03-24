<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $roles = ['super-admin', 'company-admin', 'user'];

        $allowedPermissions = $supeAdmPermissions = $adminPermissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'office-list',
            'office-create',
            'office-delete',
            'office-edit',
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

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $superAdminRole = Role::findByName('super-admin');
        $adminRole = Role::findByName('company-admin');
        $userRole = Role::findByName('user');       
        
        $superAdminRole->syncPermissions($allowedPermissions);
        $adminRole->syncPermissions($allowedPermissions);

        $userRole->syncPermissions([
            'document-list',
            'document-create',
            'document-edit',
            'document-delete',
            'document-release',
            'document-receive'
        ]);
    }
}
