<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Dashboard
            'dashboard.view',
            
            // Sales & Orders
            'orders.view', 'orders.create', 'orders.edit', 'orders.delete',
            'customers.view', 'customers.create', 'customers.edit', 'customers.delete',
            'products.view', 'products.create', 'products.edit', 'products.delete',
            'invoices.view', 'invoices.create', 'invoices.edit', 'invoices.delete',
            
            // Production
            'production.view', 'production.create', 'production.edit', 'production.delete',
            'production-orders.view', 'production-orders.create', 'production-orders.edit', 'production-orders.delete',
            'bom.view', 'bom.create', 'bom.edit', 'bom.delete',
            
            // Inventory & Materials
            'materials.view', 'materials.create', 'materials.edit', 'materials.delete',
            'warehouses.view', 'warehouses.create', 'warehouses.edit', 'warehouses.delete',
            'inventory.view', 'inventory.create', 'inventory.edit', 'inventory.delete',
            
            // Purchasing
            'purchases.view', 'purchases.create', 'purchases.edit', 'purchases.delete',
            'suppliers.view', 'suppliers.create', 'suppliers.edit', 'suppliers.delete',
            
            // Human Resources
            'employees.view', 'employees.create', 'employees.edit', 'employees.delete',
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
            
            // Accounting & Finance
            'accounting.view', 'accounting.create', 'accounting.edit', 'accounting.delete',
            'journal-entries.view', 'journal-entries.create', 'journal-entries.edit', 'journal-entries.delete',
            'payroll.view', 'payroll.create', 'payroll.edit', 'payroll.delete',
            'financial-reports.view',
            
            // Cost Management
            'cost-management.view', 'cost-management.edit',
            
            // Reports
            'reports.sales', 'reports.inventory', 'reports.production', 'reports.profitability',
            
            // System
            'settings.view', 'settings.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $roles = [
            'super_admin' => [
                'dashboard.view',
                'orders.view', 'orders.create', 'orders.edit', 'orders.delete',
                'customers.view', 'customers.create', 'customers.edit', 'customers.delete',
                'products.view', 'products.create', 'products.edit', 'products.delete',
                'invoices.view', 'invoices.create', 'invoices.edit', 'invoices.delete',
                'production.view', 'production.create', 'production.edit', 'production.delete',
                'production-orders.view', 'production-orders.create', 'production-orders.edit', 'production-orders.delete',
                'bom.view', 'bom.create', 'bom.edit', 'bom.delete',
                'materials.view', 'materials.create', 'materials.edit', 'materials.delete',
                'warehouses.view', 'warehouses.create', 'warehouses.edit', 'warehouses.delete',
                'inventory.view', 'inventory.create', 'inventory.edit', 'inventory.delete',
                'purchases.view', 'purchases.create', 'purchases.edit', 'purchases.delete',
                'suppliers.view', 'suppliers.create', 'suppliers.edit', 'suppliers.delete',
                'employees.view', 'employees.create', 'employees.edit', 'employees.delete',
                'users.view', 'users.create', 'users.edit', 'users.delete',
                'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
                'accounting.view', 'accounting.create', 'accounting.edit', 'accounting.delete',
                'journal-entries.view', 'journal-entries.create', 'journal-entries.edit', 'journal-entries.delete',
                'payroll.view', 'payroll.create', 'payroll.edit', 'payroll.delete',
                'financial-reports.view',
                'cost-management.view', 'cost-management.edit',
                'reports.sales', 'reports.inventory', 'reports.production', 'reports.profitability',
                'settings.view', 'settings.edit',
            ],
            'admin' => [
                'dashboard.view',
                'orders.view', 'orders.create', 'orders.edit',
                'customers.view', 'customers.create', 'customers.edit',
                'products.view', 'products.create', 'products.edit',
                'invoices.view', 'invoices.create', 'invoices.edit',
                'production.view', 'production.create', 'production.edit',
                'production-orders.view', 'production-orders.create', 'production-orders.edit',
                'bom.view', 'bom.create', 'bom.edit',
                'materials.view', 'materials.create', 'materials.edit',
                'warehouses.view', 'warehouses.create', 'warehouses.edit',
                'inventory.view', 'inventory.create', 'inventory.edit',
                'purchases.view', 'purchases.create', 'purchases.edit',
                'suppliers.view', 'suppliers.create', 'suppliers.edit',
                'employees.view', 'employees.create', 'employees.edit',
                'users.view', 'users.create', 'users.edit',
                'accounting.view', 'accounting.create', 'accounting.edit',
                'journal-entries.view', 'journal-entries.create', 'journal-entries.edit',
                'payroll.view', 'payroll.create', 'payroll.edit',
                'financial-reports.view',
                'cost-management.view', 'cost-management.edit',
                'reports.sales', 'reports.inventory', 'reports.production', 'reports.profitability',
                'settings.view',
            ],
            'manager' => [
                'dashboard.view',
                'orders.view', 'orders.create', 'orders.edit',
                'customers.view', 'customers.create', 'customers.edit',
                'products.view', 'products.create', 'products.edit',
                'production.view', 'production.create', 'production.edit',
                'production-orders.view', 'production-orders.create', 'production-orders.edit',
                'bom.view', 'bom.create', 'bom.edit',
                'materials.view', 'materials.create', 'materials.edit',
                'warehouses.view', 'warehouses.create', 'warehouses.edit',
                'inventory.view', 'inventory.create', 'inventory.edit',
                'purchases.view', 'purchases.create', 'purchases.edit',
                'suppliers.view', 'suppliers.create', 'suppliers.edit',
                'employees.view', 'employees.create', 'employees.edit',
                'accounting.view', 'accounting.create', 'accounting.edit',
                'journal-entries.view', 'journal-entries.create', 'journal-entries.edit',
                'payroll.view', 'payroll.create', 'payroll.edit',
                'financial-reports.view',
                'cost-management.view',
                'reports.sales', 'reports.inventory', 'reports.production', 'reports.profitability',
            ],
            'accountant' => [
                'dashboard.view',
                'orders.view',
                'customers.view',
                'products.view',
                'invoices.view', 'invoices.create', 'invoices.edit',
                'accounting.view', 'accounting.create', 'accounting.edit',
                'journal-entries.view', 'journal-entries.create', 'journal-entries.edit',
                'payroll.view', 'payroll.create', 'payroll.edit',
                'financial-reports.view',
                'cost-management.view',
                'reports.sales', 'reports.inventory', 'reports.production', 'reports.profitability',
            ],
            'production_staff' => [
                'dashboard.view',
                'orders.view',
                'products.view',
                'production.view', 'production.create', 'production.edit',
                'production-orders.view', 'production-orders.create', 'production-orders.edit',
                'bom.view', 'bom.create', 'bom.edit',
                'materials.view', 'materials.create', 'materials.edit',
                'warehouses.view',
                'inventory.view', 'inventory.create', 'inventory.edit',
                'reports.production',
            ],
            'purchasing_agent' => [
                'dashboard.view',
                'orders.view',
                'products.view',
                'materials.view', 'materials.create', 'materials.edit',
                'warehouses.view',
                'inventory.view', 'inventory.create', 'inventory.edit',
                'purchases.view', 'purchases.create', 'purchases.edit',
                'suppliers.view', 'suppliers.create', 'suppliers.edit',
                'reports.inventory',
            ],
            'staff' => [
                'dashboard.view',
                'orders.view',
                'products.view',
                'materials.view',
                'warehouses.view',
                'inventory.view',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);
            $role->givePermissionTo($rolePermissions);
        }

        // Create super admin user if not exists
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@hudaerp.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password123'),
                'is_active' => true,
                'created_by' => 1,
            ]
        );

        $superAdmin->assignRole('super_admin');

        $this->command->info('Roles and permissions created successfully!');
        $this->command->info('Super Admin created: admin@hudaerp.com / password123');
    }
}