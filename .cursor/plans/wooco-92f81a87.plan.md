<!-- 92f81a87-7f34-4872-922d-17f9022eb17d 604878c5-c33d-4a28-a7a2-991bdc21f906 -->
# WooCommerce ERP Integration - Complete Enhancement Plan

## Overview

Enhance the existing ERP system to fully integrate with WooCommerce by adding cost calculation, shipping logic, automatic accounting entries, and analytics tracking without breaking current functionality.

## Database Changes

### 1. Add Missing Fields to Orders Table

Create migration `add_cost_shipping_analytics_to_orders_table.php`:

- Add shipping fields: `shipping_cost`, `shipping_country`, `order_weight`
- Add cost breakdown: `material_cost`, `labor_cost`, `overhead_cost`, `total_cost`, `profit_margin`
- Add analytics: `utm_source`, `utm_medium`, `utm_campaign`, `referrer`
- Update `Order` model fillable and casts accordingly

### 2. Add Cost Fields to Products Table  

Create migration `add_costing_fields_to_products_table.php`:

- Add `labor_cost_percentage` (default from settings)
- Add `overhead_cost_percentage` (default from settings)
- Add `suggested_price` (calculated based on cost + margin)
- Add `last_cost_calculation_date`
- Update `Product` model fillable and casts

## Payroll System Integration

### 3. Create Payroll Tables

Create migration `create_payroll_tables.php`:

**Table: payrolls**

- `employee_id`, `period_start`, `period_end`, `base_salary`, `overtime_hours`, `overtime_amount`
- `bonuses`, `deductions`, `total_amount`, `status` (draft, approved, paid)
- `payment_date`, `payment_method`, `notes`, `created_by`

**Table: employee_work_logs**

- `employee_id`, `production_stage_id` (nullable), `date`, `hours_worked`, `hourly_rate`
- `total_amount`, `work_type` (production, administrative, overtime)
- `notes`, `approved_by`, `approved_at`

### 4. Payroll Configuration in Settings

Add to settings seeder:

- `payroll.overtime_multiplier`: 1.5 (time and half)
- `payroll.working_days_per_month`: 26
- `payroll.working_hours_per_day`: 8
- `payroll.payment_day`: 25 (day of month)

## Service Layer (New Classes in app/Services/)

### 5. PayrollService

Create `app/Services/PayrollService.php`:

- `calculateMonthlyPayroll(Employee $employee, $month, $year)`: Calculate total payroll
- `getBasePayForPeriod(Employee $employee, $start, $end)`: Pro-rated salary
- `calculateOvertimePayment(Employee $employee, $hours)`: Based on overtime multiplier
- `getProductionWorkHours(Employee $employee, $start, $end)`: Sum from production_stages
- `getWorkLogHours(Employee $employee, $start, $end)`: Sum from employee_work_logs
- `calculateProductionBonus(Employee $employee, $start, $end)`: Based on completed stages
- `generatePayroll(Employee $employee, $month, $year)`: Create payroll record
- `approvePayroll(Payroll $payroll)`: Change status to approved
- `processPayment(Payroll $payroll)`: Mark as paid, create accounting entry

### 6. ProductCostCalculator Service

Create `app/Services/ProductCostCalculator.php`:

- `calculateMaterialCost(Product $product)`: Sum BOM items costs from current material prices
- `calculateLaborCost(Product $product, $quantity = 1)`: Calculate based on production time and hourly rates
  - Get average time from production_stages.duration_minutes
  - Get average hourly rate from employees who work on this product type
  - Labor Cost = (Average Time in Hours × Average Hourly Rate) × Quantity
- `calculateLaborCostFromSettings(Product $product, $materialCost)`: Fallback: Apply labor percentage from settings
- `calculateOverheadCost(Product $product, $materialCost)`: Apply overhead percentage from settings
- `calculateTotalCost(Product $product, $quantity = 1)`: Material + Labor + Overhead
- `updateProductCost(Product $product)`: Save calculated cost to product.cost field
- `calculateBatchCosts()`: Recalculate all products (for scheduled jobs)
- `getAverageLaborTimeForProduct(Product $product)`: Query production_stages for historical average

### 4. ShippingCalculator Service

Create `app/Services/ShippingCalculator.php`:

- `calculateShippingCost(string $country, float $weight)`: 
  - KW: Fixed 2 KWD
  - GCC (SA, AE, QA, BH, OM): 7 KWD for ≤2kg, then +2 KWD per additional kg
  - Other: Configurable in settings
- `getOrderWeight(Order $order)`: Sum product weights × quantities
- Store rates in Settings table for easy configuration

### 5. AccountingService Service

Create `app/Services/AccountingService.php`:

- `recordOrderRevenue(Order $order)`: Create accounting entry for order total
- `recordShippingRevenue(Order $order)`: Create entry for shipping income
- `recordCostOfGoods(Order $order)`: Record material/labor/overhead costs
- `recordProfitEntry(Order $order)`: Calculate and record profit
- `autoProcessOrder(Order $order)`: Call all above when order is completed
- Reference existing `Accounting` and `JournalEntry` models

## Update WooCommerce Sync Command

### 6. Enhance SyncWooCommerceCommand

Update `app/Console/Commands/SyncWooCommerceCommand.php`:

**In syncProducts():**

- Extract `weight` from WooCommerce product data
- Call `ProductCostCalculator::updateProductCost()` after creating/updating product
- Store product images properly

**In syncOrders():**

- Extract shipping data: `$wooOrder['shipping']['country']`
- Extract meta data: `utm_source`, `utm_medium`, `utm_campaign` from `$wooOrder['meta_data']`
- Calculate shipping cost using `ShippingCalculator`
- Calculate order costs using `ProductCostCalculator` for each item
- Store all cost breakdowns in order fields
- Calculate profit margin: `(final_amount - total_cost) / final_amount * 100`

**Add new method processOrderCosts():**

- Loop through order items
- Get product costs
- Sum material, labor, overhead
- Calculate shipping
- Store in order

**Auto-accounting integration:**

- When order status becomes 'completed', call `AccountingService::autoProcessOrder()`
- Add Observer pattern: `OrderObserver` listening to 'updated' event
- Check if status changed to 'completed', trigger accounting

## Settings Configuration

### 7. Add Default Settings via Seeder

Create `database/seeders/CostingSettingsSeeder.php`:

```
- category: 'costing', key: 'labor_cost_percentage', value: '30'
- category: 'costing', key: 'overhead_cost_percentage', value: '20'  
- category: 'costing', key: 'profit_margin_target', value: '40'
- category: 'shipping', key: 'kuwait_flat_rate', value: '2'
- category: 'shipping', key: 'gcc_base_rate', value: '7'
- category: 'shipping', key: 'gcc_additional_per_kg', value: '2'
- category: 'shipping', key: 'gcc_weight_threshold', value: '2'
```

### 8. Settings Management Interface

Update `app/Http/Controllers/SettingsController.php`:

- Add methods to manage costing and shipping settings
- Update `resources/views/settings/index.blade.php` to show cost/shipping configuration tabs

## Model Updates

### 9. Update Order Model

File: `app/Models/Order.php`

- Add new fields to fillable
- Add casts for decimal fields
- Add accessor `getProfitAmount()`: `final_amount - total_cost`
- Add method `recalculateCosts()` to trigger cost recalculation
- Add scope `profitable()`: where profit_margin > 0

### 10. Update Product Model  

File: `app/Models/Product.php`

- Add new fields to fillable
- Add method `getActiveBOM()` to get default BOM
- Add method `calculateCost()` that calls ProductCostCalculator
- Add accessor `getSuggestedPriceAttribute()` based on cost + target margin

### 11. Create OrderObserver

File: `app/Observers/OrderObserver.php`

- Listen to `updated` event
- Check if status changed to 'completed'
- Trigger `AccountingService::autoProcessOrder()`
- Register in `app/Providers/AppServiceProvider.php`

## Controller Updates

### 12. Update OrderController

File: `app/Http/Controllers/OrderController.php`

- In `store()`: Calculate costs and shipping before saving
- Add `recalculateCosts(Order $order)` endpoint
- Add `viewCostBreakdown(Order $order)` to show detailed costs

### 13. Update ProductController

File: `app/Http/Controllers/ProductController.php`

- Add `calculateCost(Product $product)` endpoint
- Add `updatePricing(Product $product)` to update price based on cost
- Show cost breakdown in product details

## View Updates

### 14. Enhance Order Views

- `resources/views/orders/show.blade.php`: Add cost breakdown section
- Show: Material Cost, Labor Cost, Overhead Cost, Shipping Cost, Total Cost, Revenue, Profit, Margin %
- Add analytics section: Show UTM source, medium, campaign

### 15. Enhance Product Views  

- `resources/views/products/show.blade.php`: Add costing section
- Show: BOM Cost, Labor %, Overhead %, Total Cost, Current Price, Suggested Price, Margin
- Add button "Recalculate Cost"

### 16. Add Analytics Dashboard Widgets

- `resources/views/dashboard.blade.php`: Add profit analytics
- Show: Total Revenue, Total Cost, Total Profit, Average Margin
- Add chart: Orders by UTM Source
- Add table: Top profitable products

## Reports Enhancement

### 17. Add Profitability Report

Create `resources/views/reports/profitability.blade.php`:

- Order profitability by date range
- Product profitability analysis  
- Customer profitability (total orders - total costs)
- Shipping cost vs revenue analysis
- Marketing channel ROI (by UTM source)

## Routes Updates

### 18. Add New Routes

File: `routes/web.php`:

```php
// Order costing routes
Route::post('orders/{order}/recalculate-costs', [OrderController::class, 'recalculateCosts']);
Route::get('orders/{order}/cost-breakdown', [OrderController::class, 'costBreakdown']);

// Product costing routes  
Route::post('products/{product}/calculate-cost', [ProductController::class, 'calculateCost']);
Route::post('products/{product}/update-pricing', [ProductController::class, 'updatePricing']);

// Reports
Route::get('reports/profitability', [ReportController::class, 'profitability']);

// Settings
Route::get('settings/costing', [SettingsController::class, 'costing']);
Route::post('settings/costing', [SettingsController::class, 'updateCosting']);
```

## Testing & Validation

### 19. Create Artisan Command for Bulk Operations

`app/Console/Commands/RecalculateProductCostsCommand.php`:

- Loop through all products
- Recalculate costs based on current BOM and material prices
- Update product cost fields
- Generate report of cost changes

### 20. Data Integrity Checks

- Ensure all existing orders still work
- Validate that new fields have sensible defaults  
- Test WooCommerce sync doesn't break existing data
- Verify accounting entries are created correctly

## Key Design Decisions

1. **Non-Destructive**: All changes are additive - no existing columns dropped or modified
2. **Service Layer**: Business logic in dedicated service classes, not in controllers
3. **Observer Pattern**: Automatic accounting triggered by events, not manual calls
4. **Settings-Driven**: Percentages and rates configurable via Settings table
5. **Backward Compatible**: Existing routes and views continue working
6. **Progressive Enhancement**: New features optional, system works without them

## Implementation Order

1. Migrations (database changes)
2. Service classes (business logic)
3. Model updates (relationships and accessors)
4. Observer (automatic accounting)
5. Update sync command (WooCommerce integration)
6. Controller endpoints (API)
7. Views (UI)
8. Settings seeder (defaults)
9. Routes (connectivity)
10. Testing & validation

### To-dos

- [ ] Create migrations for orders and products tables to add cost, shipping, and analytics fields
- [ ] Build service layer: ProductCostCalculator, ShippingCalculator, AccountingService
- [ ] Update Order and Product models with new fields, casts, and methods
- [ ] Create OrderObserver for automatic accounting and register in AppServiceProvider
- [ ] Enhance SyncWooCommerceCommand to extract and process cost/shipping/analytics data
- [ ] Update OrderController and ProductController with costing endpoints
- [ ] Create CostingSettingsSeeder and update SettingsController for configuration
- [ ] Enhance order and product views to display cost breakdowns and analytics
- [ ] Create profitability report view and dashboard analytics widgets
- [ ] Add new routes for costing, analytics, and settings endpoints
- [ ] Create RecalculateProductCostsCommand for batch processing
- [ ] Test all functionality, validate data integrity, and ensure backward compatibility