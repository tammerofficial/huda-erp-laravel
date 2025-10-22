<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncWooCommerceCommand extends Command
{
    protected $signature = 'woocommerce:sync';
    protected $description = 'Sync orders, customers, and products from WooCommerce every 5 minutes';

    protected $storeUrl;
    protected $consumerKey;
    protected $consumerSecret;

    public function __construct()
    {
        parent::__construct();
        $this->storeUrl = env('WOOCOMMERCE_STORE_URL');
        $this->consumerKey = env('WOOCOMMERCE_CONSUMER_KEY');
        $this->consumerSecret = env('WOOCOMMERCE_CONSUMER_SECRET');
    }

    public function handle()
    {
        $this->info('Starting WooCommerce sync...');
        
        try {
            // Sync customers first
            $this->syncCustomers();
            
            // Sync products
            $this->syncProducts();
            
            // Sync orders
            $this->syncOrders();
            
            $this->info('WooCommerce sync completed successfully!');
            Log::info('WooCommerce auto-sync completed successfully');
            
        } catch (\Exception $e) {
            $this->error('WooCommerce sync failed: ' . $e->getMessage());
            Log::error('WooCommerce auto-sync failed: ' . $e->getMessage());
        }
    }

    protected function syncCustomers()
    {
        $this->info('Syncing customers...');
        
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->withOptions(['verify' => false]) // Disable SSL verification if needed
            ->timeout(60)
            ->get($this->storeUrl . 'wp-json/wc/v3/customers', [
                'per_page' => 20,
                'orderby' => 'date',
                'order' => 'desc'
            ]);

        if (!$response->successful()) {
            $this->error("Response status: {$response->status()}");
            $this->error("Response body: " . $response->body());
            throw new \Exception('Failed to fetch customers from WooCommerce');
        }

        $customers = $response->json();
        $syncedCount = 0;

        foreach ($customers as $wooCustomer) {
            try {
                $this->createOrUpdateCustomer($wooCustomer);
                $syncedCount++;
                $this->info("✓ Synced customer: {$wooCustomer['email']}");
                sleep(2); // 2 second delay
            } catch (\Exception $e) {
                $this->error("✗ Failed customer {$wooCustomer['id']}: " . $e->getMessage());
                Log::error("Failed to sync customer {$wooCustomer['id']}: " . $e->getMessage());
            }
        }

        $this->info("✅ Synced {$syncedCount} customers");
    }

    protected function syncProducts()
    {
        $this->info('Syncing products...');
        
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->withOptions(['verify' => false])
            ->timeout(60)
            ->get($this->storeUrl . 'wp-json/wc/v3/products', [
                'per_page' => 20,
                'status' => 'publish',
                'orderby' => 'date',
                'order' => 'desc'
            ]);

        if (!$response->successful()) {
            $this->error("Response status: {$response->status()}");
            throw new \Exception('Failed to fetch products from WooCommerce');
        }

        $products = $response->json();
        $syncedCount = 0;

        foreach ($products as $wooProduct) {
            try {
                $this->createOrUpdateProduct($wooProduct);
                $syncedCount++;
                $this->info("✓ Synced product: {$wooProduct['name']}");
                sleep(2); // 2 second delay
            } catch (\Exception $e) {
                $this->error("✗ Failed product {$wooProduct['id']}: " . $e->getMessage());
                Log::error("Failed to sync product {$wooProduct['id']}: " . $e->getMessage());
            }
        }

        $this->info("✅ Synced {$syncedCount} products");
    }

    protected function syncOrders()
    {
        $this->info('Syncing orders...');
        
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->timeout(30)
            ->get($this->storeUrl . 'wp-json/wc/v3/orders', [
                'per_page' => 20,
                'status' => 'any',
                'orderby' => 'date',
                'order' => 'desc'
            ]);

        if (!$response->successful()) {
            throw new \Exception('Failed to fetch orders from WooCommerce');
        }

        $orders = $response->json();
        $syncedCount = 0;

        foreach ($orders as $wooOrder) {
            try {
                $this->createOrUpdateOrder($wooOrder);
                $syncedCount++;
                $this->info("Synced order: #{$wooOrder['number']}");
                sleep(2); // 2 second delay
            } catch (\Exception $e) {
                Log::error("Failed to sync order {$wooOrder['id']}: " . $e->getMessage());
            }
        }

        $this->info("Synced {$syncedCount} orders");
    }

    protected function createOrUpdateCustomer($wooCustomer)
    {
        $existingCustomer = Customer::where('woo_id', $wooCustomer['id'])->first();
        
        if ($existingCustomer) {
            $existingCustomer->update([
                'name' => $wooCustomer['first_name'] . ' ' . $wooCustomer['last_name'],
                'email' => $wooCustomer['email'],
                'phone' => $wooCustomer['billing']['phone'] ?? $existingCustomer->phone,
                'address' => $this->formatAddress($wooCustomer['billing']) ?: $existingCustomer->address,
                'city' => $wooCustomer['billing']['city'] ?? $existingCustomer->city,
            ]);
            return $existingCustomer;
        }

        return Customer::create([
            'name' => $wooCustomer['first_name'] . ' ' . $wooCustomer['last_name'],
            'email' => $wooCustomer['email'],
            'phone' => $wooCustomer['billing']['phone'] ?? null,
            'address' => $this->formatAddress($wooCustomer['billing']),
            'city' => $wooCustomer['billing']['city'] ?? null,
            'country' => $wooCustomer['billing']['country'] ?? 'Kuwait',
            'woo_id' => $wooCustomer['id'],
            'customer_type' => 'individual',
            'is_active' => true,
        ]);
    }

    protected function createOrUpdateProduct($wooProduct)
    {
        $existingProduct = Product::where('woo_id', $wooProduct['id'])->first();
        
        $productData = [
            'name' => $wooProduct['name'],
            'price' => $wooProduct['price'],
            'description' => $wooProduct['description'],
            'is_active' => $wooProduct['status'] === 'publish',
            'stock_quantity' => $wooProduct['stock_quantity'] ?? 0,
            'weight' => $wooProduct['weight'] ?? null,
            'image_url' => $wooProduct['images'][0]['src'] ?? null,
        ];

        if ($existingProduct) {
            $existingProduct->update($productData);
            $product = $existingProduct;
        } else {
            $product = Product::create(array_merge($productData, [
                'sku' => $wooProduct['sku'] ?: 'WOO-' . $wooProduct['id'],
                'category' => $wooProduct['categories'][0]['name'] ?? null,
                'woo_id' => $wooProduct['id'],
                'product_type' => 'standard',
                'unit' => 'piece',
            ]));
        }

        // Update product cost if BOM exists
        try {
            $costCalculator = app(\App\Services\ProductCostCalculator::class);
            if ($product->billOfMaterials()->where('is_default', true)->exists()) {
                $costCalculator->updateProductCost($product);
            }
        } catch (\Exception $e) {
            Log::warning("Failed to calculate cost for product {$product->id}: " . $e->getMessage());
        }

        return $product;
    }

    protected function createOrUpdateOrder($wooOrder)
    {
        $existingOrder = Order::where('woo_id', $wooOrder['id'])->first();
        
        if ($existingOrder) {
            $existingOrder->update([
                'status' => $this->mapWooCommerceStatus($wooOrder['status']),
                'payment_status' => $this->mapWooCommercePaymentStatus($wooOrder['status']),
            ]);
            
            // Recalculate costs if order is updated
            $this->processOrderCosts($existingOrder, $wooOrder);
            
            return $existingOrder->fresh();
        }

        // Create customer if not exists
        $customer = $this->createOrUpdateCustomerFromBilling($wooOrder['billing']);

        // Extract analytics data from meta_data
        $utmSource = $this->extractMetaValue($wooOrder['meta_data'] ?? [], 'utm_source');
        $utmMedium = $this->extractMetaValue($wooOrder['meta_data'] ?? [], 'utm_medium');
        $utmCampaign = $this->extractMetaValue($wooOrder['meta_data'] ?? [], 'utm_campaign');
        $referrer = $this->extractMetaValue($wooOrder['meta_data'] ?? [], '_wp_http_referer');

        // Create order
        $order = Order::create([
            'customer_id' => $customer->id,
            'order_number' => $wooOrder['number'],
            'order_date' => $wooOrder['date_created'],
            'total_amount' => $wooOrder['total'],
            'tax_amount' => $wooOrder['total_tax'],
            'discount_amount' => $wooOrder['discount_total'],
            'final_amount' => $wooOrder['total'],
            'status' => $this->mapWooCommerceStatus($wooOrder['status']),
            'payment_status' => $this->mapWooCommercePaymentStatus($wooOrder['status']),
            'payment_type' => $this->mapWooCommercePaymentMethod($wooOrder['payment_method'] ?? ''),
            'woo_id' => $wooOrder['id'],
            'delivery_date' => $wooOrder['date_completed'],
            'shipping_address' => $this->formatAddress($wooOrder['shipping']),
            'shipping_country' => $wooOrder['shipping']['country'] ?? 'KW',
            'created_by' => 1, // System user
            // Analytics fields
            'utm_source' => $utmSource,
            'utm_medium' => $utmMedium,
            'utm_campaign' => $utmCampaign,
            'referrer' => $referrer,
        ]);

        // Create order items
        foreach ($wooOrder['line_items'] as $lineItem) {
            $product = $this->createOrUpdateProductFromLineItem($lineItem);
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $lineItem['quantity'],
                'unit_price' => $lineItem['price'],
                'total_price' => $lineItem['total'],
            ]);
        }

        // Process costs and shipping
        $this->processOrderCosts($order, $wooOrder);

        return $order->fresh();
    }

    protected function mapWooCommercePaymentMethod(string $method): ?string
    {
        $method = strtolower($method);
        // Basic mapping for common gateways to our enum
        if (in_array($method, ['cash', 'cod', 'cash_on_delivery'])) {
            return 'cash';
        }
        if (in_array($method, ['cheque', 'bank', 'bank_transfer', 'wire'])) {
            return 'bank_transfer';
        }
        if (in_array($method, ['card', 'credit_card', 'stripe', 'tap', 'myfatoorah', 'visa', 'mastercard', 'knet'])) {
            return 'card';
        }
        if (in_array($method, ['credit', 'invoice'])) {
            return 'credit';
        }
        // For BNPL or unknown providers (tabby, tamara, etc.)
        return 'card';
    }

    protected function createOrUpdateCustomerFromBilling($billing)
    {
        $email = $billing['email'];
        $customer = Customer::where('email', $email)->first();
        
        if (!$customer) {
            $customer = Customer::create([
                'name' => $billing['first_name'] . ' ' . $billing['last_name'],
                'email' => $email,
                'phone' => $billing['phone'] ?? null,
                'address' => $this->formatAddress($billing),
                'city' => $billing['city'] ?? null,
                'country' => $billing['country'] ?? 'Kuwait',
                'customer_type' => 'individual',
                'is_active' => true,
            ]);
        }
        
        return $customer;
    }

    protected function createOrUpdateProductFromLineItem($lineItem)
    {
        $sku = $lineItem['sku'] ?: 'WOO-' . $lineItem['product_id'];
        $product = Product::where('sku', $sku)->first();
        
        if (!$product) {
            $product = Product::create([
                'name' => $lineItem['name'],
                'sku' => $sku,
                'price' => $lineItem['price'],
                'product_type' => 'standard',
                'is_active' => true,
                'stock_quantity' => 0,
                'unit' => 'piece',
            ]);
        }
        
        return $product;
    }

    protected function mapWooCommerceStatus($status)
    {
        $statusMap = [
            'pending' => 'pending',
            'processing' => 'in-production',
            'on-hold' => 'on-hold',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
        ];

        return $statusMap[$status] ?? 'pending';
    }

    protected function mapWooCommercePaymentStatus($status)
    {
        $paymentMap = [
            'pending' => 'pending',
            'processing' => 'pending',
            'on-hold' => 'pending',
            'completed' => 'paid',
            'cancelled' => 'pending',
        ];

        return $paymentMap[$status] ?? 'pending';
    }

    protected function formatAddress($address)
    {
        $parts = array_filter([
            $address['address_1'] ?? '',
            $address['address_2'] ?? '',
            $address['city'] ?? '',
            $address['state'] ?? '',
            $address['postcode'] ?? '',
            $address['country'] ?? ''
        ]);
        
        return implode(', ', $parts);
    }

    /**
     * Process order costs - calculate material, labor, overhead, and shipping
     */
    protected function processOrderCosts(Order $order, array $wooOrder)
    {
        try {
            $costCalculator = app(\App\Services\ProductCostCalculator::class);
            $shippingCalculator = app(\App\Services\ShippingCalculator::class);

            // Reload order with items
            $order->load('orderItems.product');

            // Calculate product costs
            $orderItems = $order->orderItems->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                ];
            })->toArray();

            $costs = $costCalculator->calculateOrderCosts($orderItems);

            // Calculate shipping
            $shipping = $shippingCalculator->calculateOrderShipping($order);

            // Calculate profit margin
            $totalCost = $costs['total_cost'] + $shipping['shipping_cost'];
            $profitMargin = $order->final_amount > 0 
                ? (($order->final_amount - $totalCost) / $order->final_amount) * 100 
                : 0;

            $order->update([
                'material_cost' => $costs['material_cost'],
                'labor_cost' => $costs['labor_cost'],
                'overhead_cost' => $costs['overhead_cost'],
                'total_cost' => $totalCost,
                'shipping_cost' => $shipping['shipping_cost'],
                'shipping_country' => $shipping['shipping_country'],
                'order_weight' => $shipping['order_weight'],
                'profit_margin' => round($profitMargin, 2),
            ]);

            Log::info("Costs calculated for Order #{$order->order_number}: Total Cost = {$totalCost} KWD, Profit Margin = {$profitMargin}%");
        } catch (\Exception $e) {
            Log::error("Failed to calculate costs for Order #{$order->order_number}: " . $e->getMessage());
        }
    }

    /**
     * Extract meta value from WooCommerce meta_data array
     */
    protected function extractMetaValue(array $metaData, string $key): ?string
    {
        foreach ($metaData as $meta) {
            if (isset($meta['key']) && $meta['key'] === $key) {
                return $meta['value'] ?? null;
            }
        }
        return null;
    }
}