<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class MaterialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = Supplier::all();
        
        if ($suppliers->isEmpty()) {
            $this->command->warn('⚠️  No suppliers found. Please run SuppliersSeeder first.');
            return;
        }

        $materials = [
            // Fabrics
            [
                'name' => 'Premium Black Crepe Fabric',
                'sku' => 'FAB-BLK-CREPE-001',
                'unit' => 'meter',
                'unit_cost' => 3.500,
                'category' => 'Fabric',
                'reorder_level' => 100,
                'supplier_id' => $suppliers->random()->id,
                'description' => 'High quality black crepe fabric for abayas',
                'is_active' => true,
            ],
            [
                'name' => 'Navy Blue Georgette',
                'sku' => 'FAB-NAVY-GEO-002',
                'unit' => 'meter',
                'unit_cost' => 4.200,
                'category' => 'Fabric',
                'reorder_level' => 80,
                'supplier_id' => $suppliers->random()->id,
                'description' => 'Elegant navy georgette for premium garments',
                'is_active' => true,
            ],
            [
                'name' => 'White Chiffon Fabric',
                'sku' => 'FAB-WHT-CHIF-003',
                'unit' => 'meter',
                'unit_cost' => 2.800,
                'category' => 'Fabric',
                'reorder_level' => 100,
                'supplier_id' => $suppliers->random()->id,
                'description' => 'Light white chiffon for overlays',
                'is_active' => true,
            ],
            [
                'name' => 'Burgundy Velvet Fabric',
                'sku' => 'FAB-BURG-VEL-004',
                'unit' => 'meter',
                'unit_cost' => 6.500,
                'category' => 'Fabric',
                'reorder_level' => 50,
                'supplier_id' => $suppliers->random()->id,
                'description' => 'Luxury burgundy velvet for winter collection',
                'is_active' => true,
            ],
            [
                'name' => 'Beige Linen Blend',
                'sku' => 'FAB-BGE-LIN-005',
                'unit' => 'meter',
                'unit_cost' => 3.200,
                'category' => 'Fabric',
                'reorder_level' => 75,
                'supplier_id' => $suppliers->random()->id,
                'description' => 'Natural beige linen blend for summer wear',
                'is_active' => true,
            ],
            
            // Threads
            [
                'name' => 'Black Polyester Thread - 5000m',
                'sku' => 'THR-BLK-POLY-001',
                'unit' => 'spool',
                'unit_cost' => 2.500,
                'category' => 'Thread',
                'reorder_level' => 50,
                'supplier_id' => $suppliers->random()->id,
                'description' => 'Heavy duty black thread for sewing machines',
                'is_active' => true,
            ],
            [
                'name' => 'White Polyester Thread - 5000m',
                'sku' => 'THR-WHT-POLY-002',
                'unit' => 'spool',
                'unit_cost' => 2.500,
                'category' => 'Thread',
                'reorder_level' => 50,
                'supplier_id' => $suppliers->random()->id,
                'description' => 'White thread for general sewing',
                'is_active' => true,
            ],
            [
                'name' => 'Gold Embroidery Thread',
                'sku' => 'THR-GLD-EMB-003',
                'unit' => 'spool',
                'unit_cost' => 4.500,
                'category' => 'Thread',
                'reorder_level' => 30,
                'supplier_id' => $suppliers->random()->id,
                'description' => 'Premium gold thread for embroidery work',
                'is_active' => true,
            ],
            
            // Accessories
            [
                'name' => 'Black Invisible Zipper - 60cm',
                'sku' => 'ZIP-BLK-INV-60',
                'unit' => 'piece',
                'unit_cost' => 0.850,
                'category' => 'Zipper',
                'reorder_level' => 100,
                'supplier_id' => $suppliers->random()->id,
                'description' => '60cm black invisible zipper for abayas',
                'is_active' => true,
            ],
            [
                'name' => 'Pearl Buttons - 15mm',
                'sku' => 'BTN-PEARL-15MM',
                'unit' => 'piece',
                'unit_cost' => 0.250,
                'category' => 'Button',
                'reorder_level' => 200,
                'supplier_id' => $suppliers->random()->id,
                'description' => 'White pearl buttons 15mm diameter',
                'is_active' => true,
            ],
            [
                'name' => 'Elastic Band - 2cm width',
                'sku' => 'ELA-BLK-2CM',
                'unit' => 'meter',
                'unit_cost' => 0.450,
                'category' => 'Elastic',
                'reorder_level' => 150,
                'supplier_id' => $suppliers->random()->id,
                'description' => 'Black elastic band for sleeves and cuffs',
                'is_active' => true,
            ],
            [
                'name' => 'Lace Trim - Floral Pattern',
                'sku' => 'LACE-FLR-BLK',
                'unit' => 'meter',
                'unit_cost' => 1.800,
                'category' => 'Lace',
                'reorder_level' => 80,
                'supplier_id' => $suppliers->random()->id,
                'description' => 'Black floral lace trim for decoration',
                'is_active' => true,
            ],
            
            // Embellishments
            [
                'name' => 'Crystal Beads - Clear',
                'sku' => 'BEAD-CRYS-CLR',
                'unit' => 'pack',
                'unit_cost' => 5.500,
                'category' => 'Embellishment',
                'reorder_level' => 40,
                'supplier_id' => $suppliers->random()->id,
                'description' => 'Clear crystal beads for embellishment - 100 pcs/pack',
                'is_active' => true,
            ],
            [
                'name' => 'Sequins - Silver',
                'sku' => 'SEQ-SLV-5MM',
                'unit' => 'pack',
                'unit_cost' => 3.200,
                'category' => 'Embellishment',
                'reorder_level' => 50,
                'supplier_id' => $suppliers->random()->id,
                'description' => 'Silver sequins 5mm - 500 pcs/pack',
                'is_active' => true,
            ],
            
            // Packaging
            [
                'name' => 'Gift Box - Premium Large',
                'sku' => 'BOX-GIFT-LRG',
                'unit' => 'piece',
                'unit_cost' => 1.500,
                'category' => 'Packaging',
                'reorder_level' => 100,
                'supplier_id' => $suppliers->random()->id,
                'description' => 'Premium gift box for abaya packaging',
                'is_active' => true,
            ],
            [
                'name' => 'Tissue Paper - White',
                'sku' => 'TISSUE-WHT-A3',
                'unit' => 'pack',
                'unit_cost' => 2.800,
                'category' => 'Packaging',
                'reorder_level' => 50,
                'supplier_id' => $suppliers->random()->id,
                'description' => 'White tissue paper for wrapping - 100 sheets/pack',
                'is_active' => true,
            ],
        ];

        foreach ($materials as $material) {
            Material::updateOrCreate(
                ['sku' => $material['sku']],
                $material
            );
        }

        $this->command->info('✅ ' . count($materials) . ' materials seeded successfully!');
    }
}

