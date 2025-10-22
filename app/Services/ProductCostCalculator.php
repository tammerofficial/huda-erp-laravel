<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductionStage;
use App\Models\Employee;
use App\Models\Setting;
use App\Models\BomItem;
use Illuminate\Support\Facades\DB;

class ProductCostCalculator
{
    /**
     * Calculate material cost from BOM
     */
    public function calculateMaterialCost(Product $product): float
    {
        $activeBOM = $product->billOfMaterials()
            ->where('is_default', true)
            ->where('status', 'active')
            ->first();

        if (!$activeBOM) {
            return 0;
        }

        $totalMaterialCost = $activeBOM->bomItems()
            ->join('materials', 'bom_items.material_id', '=', 'materials.id')
            ->selectRaw('SUM(bom_items.quantity * materials.unit_cost) as total')
            ->value('total');

        return (float) ($totalMaterialCost ?? 0);
    }

    /**
     * Calculate labor cost based on actual production time and hourly rates
     */
    public function calculateLaborCost(Product $product, int $quantity = 1): float
    {
        $averageTimeInHours = $this->getAverageLaborTimeForProduct($product);

        if ($averageTimeInHours > 0) {
            $averageHourlyRate = $this->getAverageHourlyRateForProduct($product);
            return $averageTimeInHours * $averageHourlyRate * $quantity;
        }

        // Fallback to percentage-based calculation
        $materialCost = $this->calculateMaterialCost($product);
        return $this->calculateLaborCostFromSettings($product, $materialCost);
    }

    /**
     * Calculate labor cost from settings (fallback method)
     */
    public function calculateLaborCostFromSettings(Product $product, float $materialCost): float
    {
        $laborPercentage = $product->labor_cost_percentage 
            ?? (float) Setting::where('category', 'costing')
                ->where('key', 'labor_cost_percentage')
                ->value('value') 
            ?? 30;

        return ($materialCost * $laborPercentage) / 100;
    }

    /**
     * Calculate overhead cost from settings
     */
    public function calculateOverheadCost(Product $product, float $materialCost): float
    {
        $overheadPercentage = $product->overhead_cost_percentage 
            ?? (float) Setting::where('category', 'costing')
                ->where('key', 'overhead_cost_percentage')
                ->value('value') 
            ?? 20;

        return ($materialCost * $overheadPercentage) / 100;
    }

    /**
     * Calculate total cost
     */
    public function calculateTotalCost(Product $product, int $quantity = 1): array
    {
        $materialCost = $this->calculateMaterialCost($product) * $quantity;
        $laborCost = $this->calculateLaborCost($product, $quantity);
        $overheadCost = $this->calculateOverheadCost($product, $materialCost);

        $totalCost = $materialCost + $laborCost + $overheadCost;

        return [
            'material_cost' => round($materialCost, 2),
            'labor_cost' => round($laborCost, 2),
            'overhead_cost' => round($overheadCost, 2),
            'total_cost' => round($totalCost, 2),
            'unit_cost' => round($totalCost / $quantity, 2),
        ];
    }

    /**
     * Update product cost field
     */
    public function updateProductCost(Product $product): Product
    {
        $costs = $this->calculateTotalCost($product, 1);

        $product->update([
            'cost' => $costs['total_cost'],
            'last_cost_calculation_date' => now(),
        ]);

        // Calculate suggested price based on target margin
        $targetMargin = (float) Setting::where('category', 'costing')
            ->where('key', 'profit_margin_target')
            ->value('value') ?? 40;

        $suggestedPrice = $costs['total_cost'] / (1 - ($targetMargin / 100));

        $product->update(['suggested_price' => round($suggestedPrice, 2)]);

        return $product->fresh();
    }

    /**
     * Calculate batch costs for all products
     */
    public function calculateBatchCosts(): array
    {
        $products = Product::where('is_active', true)->get();
        $updated = [];
        $errors = [];

        foreach ($products as $product) {
            try {
                $this->updateProductCost($product);
                $updated[] = $product->id;
            } catch (\Exception $e) {
                $errors[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'updated_count' => count($updated),
            'error_count' => count($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Get average labor time for a product from historical production data
     */
    public function getAverageLaborTimeForProduct(Product $product): float
    {
        $avgMinutes = ProductionStage::whereHas('productionOrder', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->where('status', 'completed')
            ->whereNotNull('duration_minutes')
            ->avg('duration_minutes');

        if (!$avgMinutes) {
            return 0;
        }

        return $avgMinutes / 60; // Convert to hours
    }

    /**
     * Get average hourly rate for employees who work on this product type
     */
    protected function getAverageHourlyRateForProduct(Product $product): float
    {
        // Get employees who have worked on this product
        $employeeIds = ProductionStage::whereHas('productionOrder', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->where('status', 'completed')
            ->whereNotNull('employee_id')
            ->distinct()
            ->pluck('employee_id');

        if ($employeeIds->isEmpty()) {
            // Fallback: use all production employees
            $averageSalary = Employee::where('employment_status', 'active')
                ->whereIn('department', ['production', 'manufacturing'])
                ->whereNotNull('salary')
                ->avg('salary');
        } else {
            $averageSalary = Employee::whereIn('id', $employeeIds)
                ->whereNotNull('salary')
                ->avg('salary');
        }

        if (!$averageSalary) {
            // Ultimate fallback from settings
            $laborPercentage = (float) Setting::where('category', 'costing')
                ->where('key', 'labor_cost_percentage')
                ->value('value') ?? 30;
            
            return 0; // Will trigger percentage-based calculation
        }

        // Convert monthly salary to hourly rate
        $workingDaysPerMonth = (float) Setting::where('category', 'payroll')
            ->where('key', 'working_days_per_month')
            ->value('value') ?? 26;

        $workingHoursPerDay = (float) Setting::where('category', 'payroll')
            ->where('key', 'working_hours_per_day')
            ->value('value') ?? 8;

        return $averageSalary / ($workingDaysPerMonth * $workingHoursPerDay);
    }

    /**
     * Calculate cost for an order (multiple products with quantities)
     */
    public function calculateOrderCosts(array $orderItems): array
    {
        $totalMaterialCost = 0;
        $totalLaborCost = 0;
        $totalOverheadCost = 0;

        foreach ($orderItems as $item) {
            $product = Product::find($item['product_id']);
            $quantity = $item['quantity'] ?? 1;

            if ($product) {
                $costs = $this->calculateTotalCost($product, $quantity);
                $totalMaterialCost += $costs['material_cost'];
                $totalLaborCost += $costs['labor_cost'];
                $totalOverheadCost += $costs['overhead_cost'];
            }
        }

        return [
            'material_cost' => round($totalMaterialCost, 2),
            'labor_cost' => round($totalLaborCost, 2),
            'overhead_cost' => round($totalOverheadCost, 2),
            'total_cost' => round($totalMaterialCost + $totalLaborCost + $totalOverheadCost, 2),
        ];
    }
}

