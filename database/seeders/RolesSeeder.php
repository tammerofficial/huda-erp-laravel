<?php

namespace Database\Seeders;

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
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $accountant = Role::firstOrCreate(['name' => 'accountant']);
        $productionStaff = Role::firstOrCreate(['name' => 'production_staff']);
        $purchasingAgent = Role::firstOrCreate(['name' => 'purchasing_agent']);

        $this->command->info('✅ Roles created successfully!');
        $this->command->info('   - Super Admin');
        $this->command->info('   - Admin');
        $this->command->info('   - Manager');
        $this->command->info('   - Accountant');
        $this->command->info('   - Production Staff');
        $this->command->info('   - Purchasing Agent');
    }
}

