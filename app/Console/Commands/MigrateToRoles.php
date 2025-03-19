<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\CompanyAccount;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class MigrateToRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates existing admins to the new Spatie role-permission system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting role migration...');

        // Create roles if they don't exist
        $roles = ['super-admin', 'admin', 'user'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
            $this->info("Role '{$roleName}' created or verified.");
        }

        $this->info('Migrating existing admins to super-admin role...');
        
        // Migrate existing admins to the super-admin role
        if (Schema::hasTable('admins')) {
            $existingAdmins = Admin::all();
            $this->info("Found {$existingAdmins->count()} admins to migrate.");
            
            foreach ($existingAdmins as $admin) {
                $user = User::find($admin->user_id);
                if ($user) {
                    $user->assignRole('super-admin');
                    $this->info("Assigned super-admin role to user: {$user->name} (ID: {$user->id})");
                }
            }
        }

        $this->info('Assigning company owners the admin role...');
        
        // Assign company owners the admin role
        $companyOwners = User::whereHas('company')->get();
        $this->info("Found {$companyOwners->count()} company owners.");
        
        foreach ($companyOwners as $owner) {
            // Skip users who are already super-admins
            if (!$owner->hasRole('super-admin')) {
                $owner->assignRole('admin');
                $this->info("Assigned admin role to company owner: {$owner->name} (ID: {$owner->id})");
            } else {
                $this->info("Skipped assigning admin role to super-admin: {$owner->name} (ID: {$owner->id})");
            }
        }

        $this->info('Assigning basic user role to remaining users...');
        
        // Assign the basic user role to all remaining users
        $regularUsers = User::whereDoesntHave('roles')->get();
        $this->info("Found {$regularUsers->count()} regular users.");
        
        foreach ($regularUsers as $user) {
            $user->assignRole('user');
            $this->info("Assigned user role to: {$user->name} (ID: {$user->id})");
        }

        $this->info('Role migration completed successfully!');
    }
}
