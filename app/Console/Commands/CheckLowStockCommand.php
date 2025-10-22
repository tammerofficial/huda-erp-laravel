<?php

namespace App\Console\Commands;

use App\Models\Material;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckLowStockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:check-low
                          {--create-orders : Automatically create purchase orders for low stock materials}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for low stock materials and optionally create automatic purchase orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking for low stock materials...');
        $this->newLine();

        // Get all materials with auto-purchase enabled
        $materials = Material::where('auto_purchase_enabled', true)
            ->where('is_active', true)
            ->with(['supplier', 'inventories'])
            ->get();

        $lowStockMaterials = [];

        foreach ($materials as $material) {
            $availableQty = $material->inventories()->sum('quantity');
            
            if ($availableQty <= $material->min_stock_level) {
                $lowStockMaterials[] = [
                    'material' => $material,
                    'available' => $availableQty,
                    'min_level' => $material->min_stock_level,
                    'to_order' => $material->auto_purchase_quantity,
                ];

                $this->warn("⚠️  Low Stock: {$material->name} (SKU: {$material->sku})");
                $this->line("   Available: {$availableQty} {$material->unit}");
                $this->line("   Min Level: {$material->min_stock_level} {$material->unit}");
                $this->line("   Will Order: {$material->auto_purchase_quantity} {$material->unit}");
                
                if ($material->supplier) {
                    $this->line("   Supplier: {$material->supplier->name}");
                }
                
                $this->newLine();
            }
        }

        if (empty($lowStockMaterials)) {
            $this->info('✅ All materials are above minimum stock level!');
            return Command::SUCCESS;
        }

        $this->info("Found " . count($lowStockMaterials) . " materials below minimum stock level.");
        $this->newLine();

        // If --create-orders flag is set, create purchase orders
        if ($this->option('create-orders')) {
            $this->info('📝 Creating automatic purchase orders...');
            $this->newLine();

            $ordersCreated = $this->createAutoPurchaseOrders($lowStockMaterials);

            $this->info("✅ Created {$ordersCreated} purchase orders successfully!");
        } else {
            $this->comment('💡 Use --create-orders flag to automatically create purchase orders.');
        }

        return Command::SUCCESS;
    }

    /**
     * Create automatic purchase orders for low stock materials
     */
    protected function createAutoPurchaseOrders(array $lowStockMaterials): int
    {
        $ordersCreated = 0;
        
        // Group materials by supplier
        $materialsBySupplier = collect($lowStockMaterials)->groupBy(function ($item) {
            return $item['material']->supplier_id ?? 'no_supplier';
        });

        foreach ($materialsBySupplier as $supplierId => $items) {
            if ($supplierId === 'no_supplier') {
                // Create separate orders for materials without supplier
                foreach ($items as $item) {
                    $this->createSinglePurchaseOrder(null, [$item]);
                    $ordersCreated++;
                }
            } else {
                // Create one order per supplier with all their materials
                $this->createSinglePurchaseOrder($supplierId, $items->toArray());
                $ordersCreated++;
            }
        }

        return $ordersCreated;
    }

    /**
     * Create a single purchase order
     */
    protected function createSinglePurchaseOrder($supplierId, array $items)
    {
        DB::transaction(function () use ($supplierId, $items) {
            // Generate PO number
            $poNumber = 'AUTO-PO-' . date('Ymd') . '-' . str_pad(PurchaseOrder::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            // Calculate total
            $totalAmount = 0;
            foreach ($items as $item) {
                $totalAmount += $item['material']->unit_cost * $item['to_order'];
            }

            // Create purchase order
            $purchaseOrder = PurchaseOrder::create([
                'supplier_id' => $supplierId,
                'order_number' => $poNumber,
                'order_date' => now(),
                'expected_delivery' => now()->addDays(7),
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'final_amount' => $totalAmount,
                'notes' => '🤖 Auto-generated purchase order due to low stock levels.',
                'created_by' => 1, // System user
            ]);

            // Create purchase order items
            foreach ($items as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'material_id' => $item['material']->id,
                    'quantity' => $item['to_order'],
                    'unit_price' => $item['material']->unit_cost,
                    'total_price' => $item['material']->unit_cost * $item['to_order'],
                ]);
            }

            $supplierName = $supplierId ? PurchaseOrder::find($purchaseOrder->id)->supplier->name : 'No Supplier';
            $this->line("   ✓ Created PO #{$poNumber} for {$supplierName} - " . number_format($totalAmount, 3) . " KWD");
        });
    }
}
