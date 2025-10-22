<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Warehouse;
use App\Models\MaterialInventory;

class MaterialInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = Warehouse::all();
        
        if ($warehouses->isEmpty()) {
            $this->command->warn('No warehouses found. Please run WarehouseSeeder first.');
            return;
        }

        $materials = Material::all();
        
        if ($materials->isEmpty()) {
            $this->command->warn('No materials found. Please run MaterialSeeder first.');
            return;
        }

        $this->command->info('Creating material inventory records...');

        foreach ($materials as $material) {
            // Assign material to 1-2 random warehouses
            $assignedWarehouses = $warehouses->random(rand(1, min(2, $warehouses->count())));
            
            foreach ($assignedWarehouses as $warehouse) {
                // Check if inventory already exists
                $exists = MaterialInventory::where('material_id', $material->id)
                    ->where('warehouse_id', $warehouse->id)
                    ->exists();
                
                if (!$exists) {
                    // Generate realistic quantities based on material type
                    $quantity = $this->getRealisticQuantity($material);
                    
                    MaterialInventory::create([
                        'material_id' => $material->id,
                        'warehouse_id' => $warehouse->id,
                        'quantity' => $quantity,
                        'reorder_level' => $material->reorder_level ?? 10,
                        'max_level' => $material->max_stock ?? $quantity * 2,
                    ]);
                    
                    $this->command->info("  ✓ {$material->name} -> {$warehouse->name}: {$quantity} {$material->unit}");
                }
            }
        }

        $this->command->info('Material inventory seeding completed!');
    }

    /**
     * Get realistic quantity based on material characteristics
     */
    private function getRealisticQuantity($material): int
    {
        // Base quantities on unit type for realism
        return match($material->unit) {
            'meter' => rand(50, 200),      // Fabric rolls
            'piece' => rand(20, 100),      // Individual items
            'kg' => rand(10, 50),          // Weight-based
            'gram' => rand(100, 500),      // Small weight items
            'pack' => rand(10, 50),        // Packaged items
            'spool' => rand(15, 60),       // Thread spools
            'box' => rand(5, 30),          // Boxed items
            'pair' => rand(10, 40),        // Paired items
            'sqm' => rand(30, 150),        // Area measurements
            'liter' => rand(10, 50),       // Liquids
            default => rand(20, 100),      // Default
        };
    }
}

