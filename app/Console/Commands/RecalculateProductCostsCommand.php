<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ProductCostCalculator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RecalculateProductCostsCommand extends Command
{
    protected $signature = 'products:recalculate-costs {--product=* : Specific product IDs to recalculate}';
    protected $description = 'Recalculate costs for all products or specific products based on current BOM and material prices';

    public function handle()
    {
        $this->info('Starting product cost recalculation...');

        $costCalculator = app(ProductCostCalculator::class);

        // Get products to process
        $productIds = $this->option('product');
        
        if (!empty($productIds)) {
            $products = Product::whereIn('id', $productIds)->get();
            $this->info("Recalculating costs for " . $products->count() . " specific products...");
        } else {
            $products = Product::where('is_active', true)->get();
            $this->info("Recalculating costs for all " . $products->count() . " active products...");
        }

        $updated = 0;
        $skipped = 0;
        $errors = [];

        $progressBar = $this->output->createProgressBar($products->count());
        $progressBar->start();

        foreach ($products as $product) {
            try {
                // Only calculate if product has BOM
                if ($product->billOfMaterials()->where('is_default', true)->exists()) {
                    $oldCost = $product->cost;
                    $costCalculator->updateProductCost($product);
                    $product->refresh();
                    $newCost = $product->cost;

                    $costChange = $newCost - $oldCost;
                    $costChangePercent = $oldCost > 0 ? ($costChange / $oldCost) * 100 : 0;

                    Log::info("Product #{$product->id} ({$product->name}): Cost updated from {$oldCost} to {$newCost} KWD (Change: {$costChangePercent}%)");

                    $updated++;
                } else {
                    $skipped++;
                }
            } catch (\Exception $e) {
                $errors[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'error' => $e->getMessage(),
                ];
                Log::error("Failed to recalculate cost for Product #{$product->id}: " . $e->getMessage());
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Display summary
        $this->info("Cost Recalculation Summary:");
        $this->line("✅ Updated: {$updated} products");
        $this->line("⏭️  Skipped (no BOM): {$skipped} products");
        $this->line("❌ Errors: " . count($errors) . " products");

        if (!empty($errors)) {
            $this->newLine();
            $this->error("Errors encountered:");
            $this->table(['Product ID', 'Product Name', 'Error'], array_map(function ($error) {
                return [
                    $error['product_id'],
                    $error['product_name'],
                    $error['error'],
                ];
            }, $errors));
        }

        $this->newLine();
        $this->info('Product cost recalculation completed!');

        return Command::SUCCESS;
    }
}
