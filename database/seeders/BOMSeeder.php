<?php

namespace Database\Seeders;

use App\Models\BillOfMaterial;
use App\Models\BomItem;
use App\Models\Product;
use App\Models\Material;
use Illuminate\Database\Seeder;

class BOMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔧 Creating Bill of Materials (BOM)...');

        $products = Product::all();
        $materials = Material::all();

        if ($products->isEmpty() || $materials->isEmpty()) {
            $this->command->warn('⚠️  Products or Materials not found. Please run ProductsSeeder and MaterialsSeeder first.');
            return;
        }

        $bomData = $this->getBOMData($products, $materials);
        $createdBOMs = 0;

        foreach ($bomData as $bomInfo) {
            try {
                // Create BOM
                $bom = BillOfMaterial::create([
                    'product_id' => $bomInfo['product_id'],
                    'description' => $bomInfo['description'],
                    'version' => $bomInfo['version'],
                    'status' => 'active',
                    'is_default' => true,
                ]);

                // Create BOM Items
                foreach ($bomInfo['items'] as $index => $item) {
                    $material = Material::find($item['material_id']);
                    BomItem::create([
                        'bom_id' => $bom->id,
                        'material_id' => $item['material_id'],
                        'quantity' => $item['quantity_required'],
                        'unit' => $item['unit'],
                        'unit_cost' => $material->unit_cost,
                        'total_cost' => $item['quantity_required'] * $material->unit_cost,
                        'notes' => $item['notes'] ?? null,
                        'sequence_order' => $index + 1,
                    ]);
                }

                $createdBOMs++;
                $this->command->info("✓ Created BOM: {$bom->description} for {$bom->product->name}");

            } catch (\Exception $e) {
                $this->command->error("✗ Failed to create BOM: " . $e->getMessage());
            }
        }

        $this->command->info('');
        $this->command->info('🎉 ════════════════════════════════════════════════════════════');
        $this->command->info("✅ Created {$createdBOMs} BOMs successfully!");
        $this->command->info('🔧 Each BOM includes detailed material requirements');
        $this->command->info('📦 Ready for production planning and cost calculation');
        $this->command->info('════════════════════════════════════════════════════════════ 🎉');
        $this->command->info('');
    }

    protected function getBOMData($products, $materials): array
    {
        // Helper function to find material by SKU
        $findMaterial = function($sku) use ($materials) {
            return $materials->where('sku', $sku)->first();
        };

        return [
            // Classic Black Abaya BOM
            [
                'product_id' => $products->where('sku', 'PRD-ABA-BLK-001')->first()->id,
                'description' => 'Complete material list for Classic Black Abaya production',
                'version' => '1.0',
                'items' => [
                    [
                        'material_id' => $findMaterial('FAB-BLK-CREPE-001')->id,
                        'quantity_required' => 2.5,
                        'unit' => 'meter',
                        'notes' => 'Main fabric for body and sleeves',
                    ],
                    [
                        'material_id' => $findMaterial('THR-BLK-POLY-001')->id,
                        'quantity_required' => 1,
                        'unit' => 'spool',
                        'notes' => 'Black thread for stitching',
                    ],
                    [
                        'material_id' => $findMaterial('ZIP-BLK-INV-60')->id,
                        'quantity_required' => 1,
                        'unit' => 'piece',
                        'notes' => 'Front zipper closure',
                    ],
                    [
                        'material_id' => $findMaterial('BTN-PEARL-15MM')->id,
                        'quantity_required' => 3,
                        'unit' => 'piece',
                        'notes' => 'Decorative pearl buttons',
                    ],
                ],
            ],

            // Butterfly Abaya BOM
            [
                'product_id' => $products->where('sku', 'PRD-ABA-NAVY-002')->first()->id,
                'description' => 'Material requirements for Butterfly sleeve abaya',
                'version' => '1.0',
                'items' => [
                    [
                        'material_id' => $findMaterial('FAB-NAVY-GEO-002')->id,
                        'quantity_required' => 3.0,
                        'unit' => 'meter',
                        'notes' => 'Navy georgette for main body',
                    ],
                    [
                        'material_id' => $findMaterial('FAB-WHT-CHIF-003')->id,
                        'quantity_required' => 0.5,
                        'unit' => 'meter',
                        'notes' => 'White chiffon for lining',
                    ],
                    [
                        'material_id' => $findMaterial('THR-WHT-POLY-002')->id,
                        'quantity_required' => 1,
                        'unit' => 'spool',
                        'notes' => 'White thread for main stitching',
                    ],
                    [
                        'material_id' => $findMaterial('ELA-BLK-2CM')->id,
                        'quantity_required' => 0.3,
                        'unit' => 'meter',
                        'notes' => 'Elastic for cuffs',
                    ],
                ],
            ],

            // Embroidered Premium Abaya BOM
            [
                'product_id' => $products->where('sku', 'PRD-ABA-EMB-003')->first()->id,
                'description' => 'Luxury abaya with embroidery materials',
                'version' => '1.0',
                'items' => [
                    [
                        'material_id' => $findMaterial('FAB-BLK-CREPE-001')->id,
                        'quantity_required' => 2.8,
                        'unit' => 'meter',
                        'notes' => 'Premium black crepe base',
                    ],
                    [
                        'material_id' => $findMaterial('THR-GLD-EMB-003')->id,
                        'quantity_required' => 2,
                        'unit' => 'spool',
                        'notes' => 'Gold embroidery thread',
                    ],
                    [
                        'material_id' => $findMaterial('BEAD-CRYS-CLR')->id,
                        'quantity_required' => 1,
                        'unit' => 'pack',
                        'notes' => 'Crystal beads for embellishment',
                    ],
                    [
                        'material_id' => $findMaterial('SEQ-SLV-5MM')->id,
                        'quantity_required' => 1,
                        'unit' => 'pack',
                        'notes' => 'Silver sequins for details',
                    ],
                    [
                        'material_id' => $findMaterial('LACE-FLR-BLK')->id,
                        'quantity_required' => 0.5,
                        'unit' => 'meter',
                        'notes' => 'Floral lace trim',
                    ],
                ],
            ],

            // Silk Hijab BOM
            [
                'product_id' => $products->where('sku', 'PRD-HIJ-NAVY-002')->first()->id,
                'description' => 'Premium silk hijab material requirements',
                'version' => '1.0',
                'items' => [
                    [
                        'material_id' => $findMaterial('FAB-BLK-CREPE-001')->id,
                        'quantity_required' => 1.2,
                        'unit' => 'meter',
                        'notes' => 'Premium black crepe fabric',
                    ],
                    [
                        'material_id' => $findMaterial('THR-BLK-POLY-001')->id,
                        'quantity_required' => 0.5,
                        'unit' => 'spool',
                        'notes' => 'Fine black thread for hemming',
                    ],
                ],
            ],

            // Prayer Set BOM
            [
                'product_id' => $products->where('sku', 'PRD-PRAY-SET-001')->first()->id,
                'description' => 'Complete prayer set materials',
                'version' => '1.0',
                'items' => [
                    [
                        'material_id' => $findMaterial('FAB-BLK-CREPE-001')->id,
                        'quantity_required' => 1.0,
                        'unit' => 'meter',
                        'notes' => 'Black crepe for prayer mat',
                    ],
                    [
                        'material_id' => $findMaterial('FAB-WHT-CHIF-003')->id,
                        'quantity_required' => 0.5,
                        'unit' => 'meter',
                        'notes' => 'White chiffon for tasbih',
                    ],
                    [
                        'material_id' => $findMaterial('BEAD-CRYS-CLR')->id,
                        'quantity_required' => 1,
                        'unit' => 'pack',
                        'notes' => 'Crystal beads for tasbih',
                    ],
                    [
                        'material_id' => $findMaterial('BOX-GIFT-LRG')->id,
                        'quantity_required' => 1,
                        'unit' => 'piece',
                        'notes' => 'Premium gift box',
                    ],
                    [
                        'material_id' => $findMaterial('TISSUE-WHT-A3')->id,
                        'quantity_required' => 1,
                        'unit' => 'pack',
                        'notes' => 'White tissue for wrapping',
                    ],
                ],
            ],
        ];
    }
}
