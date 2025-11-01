<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@hudaerp.com',
            'password' => bcrypt('password'),
        ]);

        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seed all data in proper order
        $this->call([
            // System settings
            CostingSettingsSeeder::class,
            PaymentGatewaySeeder::class,
            
            // Basic entities
            WarehouseSeeder::class,
            EmployeeSeeder::class,
            CustomersSeeder::class,
            SuppliersSeeder::class,
            
            // Products and materials
            MaterialsSeeder::class,
            ProductsSeeder::class,
            MaterialInventorySeeder::class,
            BOMSeeder::class,
            
            // WooCommerce sample orders
            WooCommerceOrdersSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('🎉 ════════════════════════════════════════════════════════════');
        $this->command->info('✅ All data seeded successfully!');
        $this->command->info('');
        $this->command->info('📧 Login Credentials:');
        $this->command->info('   Email: admin@hudaerp.com');
        $this->command->info('   Password: password');
        $this->command->info('');
        $this->command->info('🌐 Access the system at: http://127.0.0.1:8000');
        $this->command->info('════════════════════════════════════════════════════════════ 🎉');
        $this->command->info('');
    }
}
