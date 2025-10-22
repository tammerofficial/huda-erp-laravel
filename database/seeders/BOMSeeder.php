<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Material;
use App\Models\BillOfMaterial;
use App\Models\BomItem;
use Illuminate\Support\Facades\DB;

class BOMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create materials first
        $this->createMaterials();
        
        // Get all products
        $products = Product::all();
        
        // Create BOM for each product
        foreach ($products as $product) {
            $this->createBOMForProduct($product);
        }
    }

    private function createMaterials()
    {
        $materials = [
            // Fabric Materials
            [
                'name' => 'Premium Cotton Fabric',
                'sku' => 'FAB-COT-001',
                'unit' => 'meter',
                'unit_cost' => 15.50,
                'category' => 'Fabric',
                'color' => 'White',
                'size' => '150cm',
                'description' => 'High quality cotton fabric for premium garments',
                'reorder_level' => 50,
                'max_stock' => 500,
                'is_active' => true,
                'auto_purchase_enabled' => true,
                'auto_purchase_quantity' => 100,
                'min_stock_level' => 20,
            ],
            [
                'name' => 'Silk Fabric',
                'sku' => 'FAB-SILK-001',
                'unit' => 'meter',
                'unit_cost' => 45.00,
                'category' => 'Fabric',
                'color' => 'Ivory',
                'size' => '140cm',
                'description' => 'Luxury silk fabric for high-end garments',
                'reorder_level' => 20,
                'max_stock' => 200,
                'is_active' => true,
                'auto_purchase_enabled' => true,
                'auto_purchase_quantity' => 50,
                'min_stock_level' => 10,
            ],
            [
                'name' => 'Wool Fabric',
                'sku' => 'FAB-WOOL-001',
                'unit' => 'meter',
                'unit_cost' => 35.00,
                'category' => 'Fabric',
                'color' => 'Black',
                'size' => '150cm',
                'description' => 'Premium wool fabric for winter collection',
                'reorder_level' => 30,
                'max_stock' => 300,
                'is_active' => true,
                'auto_purchase_enabled' => true,
                'auto_purchase_quantity' => 75,
                'min_stock_level' => 15,
            ],
            [
                'name' => 'Linen Fabric',
                'sku' => 'FAB-LINEN-001',
                'unit' => 'meter',
                'unit_cost' => 25.00,
                'category' => 'Fabric',
                'color' => 'Natural',
                'size' => '140cm',
                'description' => 'Natural linen fabric for summer collection',
                'reorder_level' => 40,
                'max_stock' => 400,
                'is_active' => true,
                'auto_purchase_enabled' => true,
                'auto_purchase_quantity' => 80,
                'min_stock_level' => 20,
            ],
            
            // Thread Materials
            [
                'name' => 'Polyester Thread',
                'sku' => 'THR-POLY-001',
                'unit' => 'spool',
                'unit_cost' => 2.50,
                'category' => 'Thread',
                'color' => 'White',
                'size' => '500m',
                'description' => 'High quality polyester thread',
                'reorder_level' => 100,
                'max_stock' => 1000,
                'is_active' => true,
                'auto_purchase_enabled' => true,
                'auto_purchase_quantity' => 200,
                'min_stock_level' => 50,
            ],
            [
                'name' => 'Cotton Thread',
                'sku' => 'THR-COT-001',
                'unit' => 'spool',
                'unit_cost' => 3.00,
                'category' => 'Thread',
                'color' => 'Black',
                'size' => '500m',
                'description' => 'Premium cotton thread',
                'reorder_level' => 80,
                'max_stock' => 800,
                'is_active' => true,
                'auto_purchase_enabled' => true,
                'auto_purchase_quantity' => 150,
                'min_stock_level' => 40,
            ],
            
            // Zipper Materials
            [
                'name' => 'Metal Zipper',
                'sku' => 'ZIP-METAL-001',
                'unit' => 'piece',
                'unit_cost' => 8.50,
                'category' => 'Hardware',
                'color' => 'Silver',
                'size' => '20cm',
                'description' => 'Heavy duty metal zipper',
                'reorder_level' => 50,
                'max_stock' => 500,
                'is_active' => true,
                'auto_purchase_enabled' => true,
                'auto_purchase_quantity' => 100,
                'min_stock_level' => 25,
            ],
            [
                'name' => 'Plastic Zipper',
                'sku' => 'ZIP-PLASTIC-001',
                'unit' => 'piece',
                'unit_cost' => 4.50,
                'category' => 'Hardware',
                'color' => 'White',
                'size' => '15cm',
                'description' => 'Lightweight plastic zipper',
                'reorder_level' => 75,
                'max_stock' => 750,
                'is_active' => true,
                'auto_purchase_enabled' => true,
                'auto_purchase_quantity' => 150,
                'min_stock_level' => 30,
            ],
            
            // Button Materials
            [
                'name' => 'Pearl Buttons',
                'sku' => 'BTN-PEARL-001',
                'unit' => 'set',
                'unit_cost' => 12.00,
                'category' => 'Hardware',
                'color' => 'White',
                'size' => '15mm',
                'description' => 'Elegant pearl buttons',
                'reorder_level' => 30,
                'max_stock' => 300,
                'is_active' => true,
                'auto_purchase_enabled' => true,
                'auto_purchase_quantity' => 60,
                'min_stock_level' => 15,
            ],
            [
                'name' => 'Metal Buttons',
                'sku' => 'BTN-METAL-001',
                'unit' => 'set',
                'unit_cost' => 6.50,
                'category' => 'Hardware',
                'color' => 'Gold',
                'size' => '12mm',
                'description' => 'Durable metal buttons',
                'reorder_level' => 50,
                'max_stock' => 500,
                'is_active' => true,
                'auto_purchase_enabled' => true,
                'auto_purchase_quantity' => 100,
                'min_stock_level' => 25,
            ],
            
            // Lining Materials
            [
                'name' => 'Polyester Lining',
                'sku' => 'LIN-POLY-001',
                'unit' => 'meter',
                'unit_cost' => 8.00,
                'category' => 'Lining',
                'color' => 'White',
                'size' => '150cm',
                'description' => 'Smooth polyester lining',
                'reorder_level' => 60,
                'max_stock' => 600,
                'is_active' => true,
                'auto_purchase_enabled' => true,
                'auto_purchase_quantity' => 120,
                'min_stock_level' => 30,
            ],
            [
                'name' => 'Silk Lining',
                'sku' => 'LIN-SILK-001',
                'unit' => 'meter',
                'unit_cost' => 25.00,
                'category' => 'Lining',
                'color' => 'Ivory',
                'size' => '140cm',
                'description' => 'Luxury silk lining',
                'reorder_level' => 20,
                'max_stock' => 200,
                'is_active' => true,
                'auto_purchase_enabled' => true,
                'auto_purchase_quantity' => 40,
                'min_stock_level' => 10,
            ],
            
            // Embellishment Materials
            [
                'name' => 'Lace Trim',
                'sku' => 'TRM-LACE-001',
                'unit' => 'meter',
                'unit_cost' => 18.00,
                'category' => 'Embellishment',
                'color' => 'White',
                'size' => '5cm',
                'description' => 'Delicate lace trim',
                'reorder_level' => 25,
                'max_stock' => 250,
                'is_active' => true,
                'auto_purchase_enabled' => true,
                'auto_purchase_quantity' => 50,
                'min_stock_level' => 12,
            ],
            [
                'name' => 'Beads',
                'sku' => 'BEAD-001',
                'unit' => 'gram',
                'unit_cost' => 0.50,
                'category' => 'Embellishment',
                'color' => 'Clear',
                'size' => '3mm',
                'description' => 'Decorative beads',
                'reorder_level' => 100,
                'max_stock' => 1000,
                'is_active' => true,
                'auto_purchase_enabled' => true,
                'auto_purchase_quantity' => 200,
                'min_stock_level' => 50,
            ],
        ];

        foreach ($materials as $materialData) {
            Material::firstOrCreate(
                ['sku' => $materialData['sku']],
                $materialData
            );
        }
    }

    private function createBOMForProduct($product)
    {
        // Create BOM for the product
        $bom = BillOfMaterial::create([
            'product_id' => $product->id,
            'version' => 1,
            'status' => 'active',
            'description' => "BOM for {$product->name}",
            'total_cost' => 0,
            'is_default' => true,
            'created_by' => 1, // Assuming admin user ID is 1
        ]);

        // Get materials
        $materials = Material::all();
        
        // Create BOM items based on product type
        $bomItems = $this->generateBOMItems($product, $materials);
        
        $totalCost = 0;
        foreach ($bomItems as $item) {
            $bomItem = BomItem::create([
                'bom_id' => $bom->id,
                'material_id' => $item['material_id'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'unit_cost' => $item['unit_cost'],
                'total_cost' => $item['quantity'] * $item['unit_cost'],
                'notes' => $item['notes'],
                'sequence_order' => $item['sequence_order'],
            ]);
            
            $totalCost += $bomItem->total_cost;
        }
        
        // Update BOM total cost
        $bom->update(['total_cost' => $totalCost]);
        
        // Update product cost
        $product->update(['cost' => $totalCost]);
    }

    private function generateBOMItems($product, $materials)
    {
        $items = [];
        $sequence = 1;
        
        // Base fabric (required for all products)
        $fabricMaterials = $materials->where('category', 'Fabric');
        if ($fabricMaterials->count() > 0) {
            $fabric = $fabricMaterials->random();
            $fabricQuantity = $this->getFabricQuantity($product);
            $items[] = [
                'material_id' => $fabric->id,
                'quantity' => $fabricQuantity,
                'unit' => $fabric->unit,
                'unit_cost' => $fabric->unit_cost,
                'notes' => 'Main fabric for the garment',
                'sequence_order' => $sequence++,
            ];
        }
        
        // Thread (required for all products)
        $threadMaterials = $materials->where('category', 'Thread');
        if ($threadMaterials->count() > 0) {
            $thread = $threadMaterials->random();
            $threadQuantity = $this->getThreadQuantity($product);
            $items[] = [
                'material_id' => $thread->id,
                'quantity' => $threadQuantity,
                'unit' => $thread->unit,
                'unit_cost' => $thread->unit_cost,
                'notes' => 'Thread for sewing',
                'sequence_order' => $sequence++,
            ];
        }
        
        // Lining (70% chance)
        if (rand(1, 100) <= 70) {
            $liningMaterials = $materials->where('category', 'Lining');
            if ($liningMaterials->count() > 0) {
                $lining = $liningMaterials->random();
                $liningQuantity = $this->getLiningQuantity($product);
                $items[] = [
                    'material_id' => $lining->id,
                    'quantity' => $liningQuantity,
                    'unit' => $lining->unit,
                    'unit_cost' => $lining->unit_cost,
                    'notes' => 'Lining fabric',
                    'sequence_order' => $sequence++,
                ];
            }
        }
        
        // Zipper (50% chance)
        if (rand(1, 100) <= 50) {
            $zipperMaterials = $materials->where('category', 'Hardware')->where('name', 'like', '%Zipper%');
            if ($zipperMaterials->count() > 0) {
                $zipper = $zipperMaterials->random();
                $items[] = [
                    'material_id' => $zipper->id,
                    'quantity' => 1,
                    'unit' => $zipper->unit,
                    'unit_cost' => $zipper->unit_cost,
                    'notes' => 'Zipper closure',
                    'sequence_order' => $sequence++,
                ];
            }
        }
        
        // Buttons (60% chance)
        if (rand(1, 100) <= 60) {
            $buttonMaterials = $materials->where('category', 'Hardware')->where('name', 'like', '%Button%');
            if ($buttonMaterials->count() > 0) {
                $button = $buttonMaterials->random();
                $buttonQuantity = $this->getButtonQuantity($product);
                $items[] = [
                    'material_id' => $button->id,
                    'quantity' => $buttonQuantity,
                    'unit' => $button->unit,
                    'unit_cost' => $button->unit_cost,
                    'notes' => 'Button closure',
                    'sequence_order' => $sequence++,
                ];
            }
        }
        
        // Embellishments (30% chance)
        if (rand(1, 100) <= 30) {
            $embellishmentMaterials = $materials->where('category', 'Embellishment');
            if ($embellishmentMaterials->count() > 0) {
                $embellishment = $embellishmentMaterials->random();
                $embellishmentQuantity = $this->getEmbellishmentQuantity($product, $embellishment);
                $items[] = [
                    'material_id' => $embellishment->id,
                    'quantity' => $embellishmentQuantity,
                    'unit' => $embellishment->unit,
                    'unit_cost' => $embellishment->unit_cost,
                    'notes' => 'Decorative embellishment',
                    'sequence_order' => $sequence++,
                ];
            }
        }
        
        return $items;
    }

    private function getFabricQuantity($product)
    {
        // Base quantity based on product name patterns
        if (str_contains($product->name, '1950s')) {
            return rand(2, 4); // Vintage style - more fabric
        } elseif (str_contains($product->name, 'FW24/25')) {
            return rand(1, 3); // Fall/Winter collection
        } else {
            return rand(1, 2); // Standard quantity
        }
    }

    private function getThreadQuantity($product)
    {
        // Thread quantity based on complexity
        if (str_contains($product->name, '1950s')) {
            return rand(3, 5); // More detailed work
        } else {
            return rand(2, 4); // Standard quantity
        }
    }

    private function getLiningQuantity($product)
    {
        // Lining quantity (usually less than main fabric)
        return rand(1, 2);
    }

    private function getButtonQuantity($product)
    {
        // Button quantity based on garment type
        if (str_contains($product->name, '1950s')) {
            return rand(4, 8); // More buttons for vintage style
        } else {
            return rand(2, 6); // Standard quantity
        }
    }

    private function getEmbellishmentQuantity($product, $embellishment)
    {
        if ($embellishment->unit === 'gram') {
            return rand(10, 50); // Beads in grams
        } else {
            return rand(1, 3); // Lace trim in meters
        }
    }
}
