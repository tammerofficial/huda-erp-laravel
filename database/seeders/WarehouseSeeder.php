<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'name' => 'Main Warehouse',
                'location' => 'Shuwaikh Industrial Area, Kuwait City',
                'capacity' => 5000,
                'manager_id' => null, // Will be set after employees are created
                'is_active' => true,
                'notes' => 'Primary warehouse for finished goods and raw materials',
            ],
            [
                'name' => 'Fabric Storage',
                'location' => 'Rai Industrial Area, Kuwait',
                'capacity' => 3000,
                'manager_id' => null,
                'is_active' => true,
                'notes' => 'Dedicated fabric and textile storage',
            ],
            [
                'name' => 'Finished Goods Warehouse',
                'location' => 'Sabhan Industrial Area, Kuwait',
                'capacity' => 2000,
                'manager_id' => null,
                'is_active' => true,
                'notes' => 'Ready-to-ship products storage',
            ],
            [
                'name' => 'Raw Materials Storage',
                'location' => 'Amghara Industrial Area, Kuwait',
                'capacity' => 4000,
                'manager_id' => null,
                'is_active' => true,
                'notes' => 'Threads, buttons, zippers and other materials',
            ],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::updateOrCreate(
                ['name' => $warehouse['name']],
                $warehouse
            );
        }

        $this->command->info('✅ ' . count($warehouses) . ' warehouses seeded successfully!');
    }
}

