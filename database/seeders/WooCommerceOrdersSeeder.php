<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class WooCommerceOrdersSeeder extends Seeder
{
    /**
     * Seed WooCommerce sample orders with realistic data
     */
    public function run(): void
    {
        $this->command->info('🛒 Creating WooCommerce sample orders...');

        // Get existing customers and products
        $customers = Customer::all();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            $this->command->warn('⚠️  Please run CustomersSeeder and ProductsSeeder first!');
            return;
        }

        // Create 15 realistic WooCommerce orders
        $orders = [
            [
                'customer' => $customers->random(),
                'order_number' => 'WOO-2025-001',
                'order_date' => Carbon::now()->subDays(5),
                'status' => 'on-hold',
                'payment_status' => 'pending',
                'payment_type' => 'card',
                'shipping_country' => 'KW',
                'utm_source' => 'google',
                'utm_medium' => 'cpc',
                'utm_campaign' => 'ramadan_2025',
                'items' => [
                    ['product' => $products->where('category', 'Abayas')->random(), 'qty' => 2, 'price' => 45.000],
                    ['product' => $products->where('category', 'Hijabs')->random(), 'qty' => 1, 'price' => 12.000],
                ],
            ],
            [
                'customer' => $customers->random(),
                'order_number' => 'WOO-2025-002',
                'order_date' => Carbon::now()->subDays(4),
                'status' => 'on-hold',
                'payment_status' => 'pending',
                'payment_type' => 'cash',
                'shipping_country' => 'KW',
                'utm_source' => 'facebook',
                'utm_medium' => 'social',
                'utm_campaign' => 'new_collection',
                'items' => [
                    ['product' => $products->where('sku', 'PRD-ABA-EMB-003')->first(), 'qty' => 1, 'price' => 85.000],
                ],
            ],
            [
                'customer' => $customers->random(),
                'order_number' => 'WOO-2025-003',
                'order_date' => Carbon::now()->subDays(3),
                'status' => 'on-hold',
                'payment_status' => 'pending',
                'payment_type' => 'card',
                'shipping_country' => 'SA',
                'utm_source' => 'instagram',
                'utm_medium' => 'social',
                'utm_campaign' => 'influencer_collab',
                'items' => [
                    ['product' => $products->where('sku', 'PRD-ABA-VEL-004')->first(), 'qty' => 1, 'price' => 95.000],
                    ['product' => $products->where('sku', 'PRD-HIJ-NAVY-002')->first(), 'qty' => 2, 'price' => 18.000],
                ],
            ],
            [
                'customer' => $customers->random(),
                'order_number' => 'WOO-2025-004',
                'order_date' => Carbon::now()->subDays(3),
                'status' => 'in-production',
                'payment_status' => 'paid',
                'payment_type' => 'card',
                'shipping_country' => 'KW',
                'utm_source' => 'email',
                'utm_medium' => 'newsletter',
                'utm_campaign' => 'winter_sale',
                'items' => [
                    ['product' => $products->where('sku', 'PRD-ABA-CHIF-005')->first(), 'qty' => 3, 'price' => 48.000],
                ],
            ],
            [
                'customer' => $customers->random(),
                'order_number' => 'WOO-2025-005',
                'order_date' => Carbon::now()->subDays(2),
                'status' => 'on-hold',
                'payment_status' => 'pending',
                'payment_type' => 'card',
                'shipping_country' => 'AE',
                'utm_source' => 'google',
                'utm_medium' => 'cpc',
                'utm_campaign' => 'dubai_launch',
                'items' => [
                    ['product' => $products->where('sku', 'PRD-ABA-CRYS-007')->first(), 'qty' => 2, 'price' => 120.000],
                    ['product' => $products->where('sku', 'PRD-PRAY-SET-001')->first(), 'qty' => 1, 'price' => 35.000],
                ],
            ],
            [
                'customer' => $customers->random(),
                'order_number' => 'WOO-2025-006',
                'order_date' => Carbon::now()->subDays(2),
                'status' => 'on-hold',
                'payment_status' => 'pending',
                'payment_type' => 'cash',
                'shipping_country' => 'KW',
                'utm_source' => 'direct',
                'utm_medium' => 'none',
                'utm_campaign' => null,
                'items' => [
                    ['product' => $products->where('sku', 'PRD-ABA-LACE-008')->first(), 'qty' => 1, 'price' => 55.000],
                    ['product' => $products->where('sku', 'PRD-UND-SET-001')->first(), 'qty' => 2, 'price' => 8.500],
                ],
            ],
            [
                'customer' => $customers->random(),
                'order_number' => 'WOO-2025-007',
                'order_date' => Carbon::now()->subDays(1),
                'status' => 'completed',
                'payment_status' => 'paid',
                'payment_type' => 'card',
                'shipping_country' => 'KW',
                'utm_source' => 'facebook',
                'utm_medium' => 'social',
                'utm_campaign' => 'flash_sale',
                'items' => [
                    ['product' => $products->where('sku', 'PRD-ABA-BLK-001')->first(), 'qty' => 2, 'price' => 45.000],
                ],
            ],
            [
                'customer' => $customers->random(),
                'order_number' => 'WOO-2025-008',
                'order_date' => Carbon::now()->subDays(1),
                'status' => 'on-hold',
                'payment_status' => 'pending',
                'payment_type' => 'card',
                'shipping_country' => 'BH',
                'utm_source' => 'google',
                'utm_medium' => 'cpc',
                'utm_campaign' => 'gcc_expansion',
                'items' => [
                    ['product' => $products->where('sku', 'PRD-ABA-NAVY-002')->first(), 'qty' => 1, 'price' => 52.000],
                    ['product' => $products->where('sku', 'PRD-HIJ-BLK-001')->first(), 'qty' => 3, 'price' => 12.000],
                ],
            ],
            [
                'customer' => $customers->random(),
                'order_number' => 'WOO-2025-009',
                'order_date' => Carbon::now()->subHours(12),
                'status' => 'on-hold',
                'payment_status' => 'pending',
                'payment_type' => 'bank_transfer',
                'shipping_country' => 'KW',
                'utm_source' => 'instagram',
                'utm_medium' => 'story',
                'utm_campaign' => 'new_arrival',
                'items' => [
                    ['product' => $products->where('sku', 'PRD-ABA-SPRT-009')->first(), 'qty' => 1, 'price' => 62.000],
                ],
            ],
            [
                'customer' => $customers->random(),
                'order_number' => 'WOO-2025-010',
                'order_date' => Carbon::now()->subHours(8),
                'status' => 'on-hold',
                'payment_status' => 'pending',
                'payment_type' => 'card',
                'shipping_country' => 'KW',
                'utm_source' => 'tiktok',
                'utm_medium' => 'video',
                'utm_campaign' => 'viral_abaya',
                'items' => [
                    ['product' => $products->where('sku', 'PRD-ABA-LIN-010')->first(), 'qty' => 2, 'price' => 68.000],
                    ['product' => $products->where('sku', 'PRD-NIQ-BLK-001')->first(), 'qty' => 1, 'price' => 15.000],
                ],
            ],
            [
                'customer' => $customers->random(),
                'order_number' => 'WOO-2025-011',
                'order_date' => Carbon::now()->subHours(6),
                'status' => 'in-production',
                'payment_status' => 'paid',
                'payment_type' => 'card',
                'shipping_country' => 'SA',
                'utm_source' => 'snapchat',
                'utm_medium' => 'ad',
                'utm_campaign' => 'riyadh_promo',
                'items' => [
                    ['product' => $products->where('sku', 'PRD-ABA-CARD-006')->first(), 'qty' => 2, 'price' => 58.000],
                ],
            ],
            [
                'customer' => $customers->random(),
                'order_number' => 'WOO-2025-012',
                'order_date' => Carbon::now()->subHours(4),
                'status' => 'on-hold',
                'payment_status' => 'pending',
                'payment_type' => 'cash',
                'shipping_country' => 'KW',
                'utm_source' => 'whatsapp',
                'utm_medium' => 'message',
                'utm_campaign' => 'vip_customer',
                'items' => [
                    ['product' => $products->where('sku', 'PRD-ABA-EMB-003')->first(), 'qty' => 1, 'price' => 85.000],
                    ['product' => $products->where('sku', 'PRD-ABA-CRYS-007')->first(), 'qty' => 1, 'price' => 120.000],
                ],
            ],
            [
                'customer' => $customers->random(),
                'order_number' => 'WOO-2025-013',
                'order_date' => Carbon::now()->subHours(2),
                'status' => 'on-hold',
                'payment_status' => 'pending',
                'payment_type' => 'card',
                'shipping_country' => 'OM',
                'utm_source' => 'google',
                'utm_medium' => 'organic',
                'utm_campaign' => null,
                'items' => [
                    ['product' => $products->where('sku', 'PRD-ABA-BLK-001')->first(), 'qty' => 3, 'price' => 45.000],
                    ['product' => $products->where('sku', 'PRD-HIJ-BLK-001')->first(), 'qty' => 3, 'price' => 12.000],
                ],
            ],
            [
                'customer' => $customers->random(),
                'order_number' => 'WOO-2025-014',
                'order_date' => Carbon::now()->subHours(1),
                'status' => 'on-hold',
                'payment_status' => 'pending',
                'payment_type' => 'card',
                'shipping_country' => 'KW',
                'utm_source' => 'facebook',
                'utm_medium' => 'retargeting',
                'utm_campaign' => 'cart_recovery',
                'items' => [
                    ['product' => $products->where('sku', 'PRD-ABA-NAVY-002')->first(), 'qty' => 1, 'price' => 52.000],
                ],
            ],
            [
                'customer' => $customers->random(),
                'order_number' => 'WOO-2025-015',
                'order_date' => Carbon::now()->subMinutes(30),
                'status' => 'on-hold',
                'payment_status' => 'pending',
                'payment_type' => 'card',
                'shipping_country' => 'KW',
                'utm_source' => 'youtube',
                'utm_medium' => 'video',
                'utm_campaign' => 'tutorial_hijab',
                'items' => [
                    ['product' => $products->where('sku', 'PRD-HIJ-NAVY-002')->first(), 'qty' => 5, 'price' => 18.000],
                    ['product' => $products->where('sku', 'PRD-UND-SET-001')->first(), 'qty' => 2, 'price' => 8.500],
                ],
            ],
        ];

        $createdCount = 0;
        foreach ($orders as $orderData) {
            try {
                // Calculate totals
                $subtotal = 0;
                foreach ($orderData['items'] as $item) {
                    $subtotal += $item['price'] * $item['qty'];
                }

                // Create order
                $order = Order::create([
                    'customer_id' => $orderData['customer']->id,
                    'order_number' => $orderData['order_number'],
                    'order_date' => $orderData['order_date'],
                    'status' => $orderData['status'],
                    'payment_status' => $orderData['payment_status'],
                    'payment_type' => $orderData['payment_type'],
                    'total_amount' => $subtotal,
                    'final_amount' => $subtotal,
                    'shipping_country' => $orderData['shipping_country'],
                    'utm_source' => $orderData['utm_source'],
                    'utm_medium' => $orderData['utm_medium'],
                    'utm_campaign' => $orderData['utm_campaign'],
                    'woo_id' => rand(10000, 99999),
                    'created_by' => 1,
                ]);

                // Create order items
                foreach ($orderData['items'] as $itemData) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $itemData['product']->id,
                        'quantity' => $itemData['qty'],
                        'unit_price' => $itemData['price'],
                        'total_price' => $itemData['price'] * $itemData['qty'],
                    ]);
                }

                // Calculate costs and shipping
                $order->recalculateCosts();

                $createdCount++;
                $this->command->info("✓ Created order: {$orderData['order_number']} - {$orderData['customer']->name}");
            } catch (\Exception $e) {
                $this->command->error("✗ Failed to create order {$orderData['order_number']}: " . $e->getMessage());
            }
        }

        $this->command->info('');
        $this->command->info('🎉 ════════════════════════════════════════════════════════════');
        $this->command->info("✅ Created {$createdCount} WooCommerce orders successfully!");
        $this->command->info('');
        $this->command->info('📊 Order Statistics:');
        $this->command->info('   - On-Hold: ' . Order::where('status', 'on-hold')->count());
        $this->command->info('   - In Production: ' . Order::where('status', 'in-production')->count());
        $this->command->info('   - Completed: ' . Order::where('status', 'completed')->count());
        $this->command->info('');
        $this->command->info('📈 Marketing Channels:');
        $this->command->info('   - Google: ' . Order::where('utm_source', 'google')->count());
        $this->command->info('   - Facebook: ' . Order::where('utm_source', 'facebook')->count());
        $this->command->info('   - Instagram: ' . Order::where('utm_source', 'instagram')->count());
        $this->command->info('   - Others: ' . Order::whereNotIn('utm_source', ['google', 'facebook', 'instagram'])->count());
        $this->command->info('');
        $this->command->info('🌍 Shipping Countries:');
        $this->command->info('   - Kuwait (KW): ' . Order::where('shipping_country', 'KW')->count());
        $this->command->info('   - Saudi Arabia (SA): ' . Order::where('shipping_country', 'SA')->count());
        $this->command->info('   - UAE (AE): ' . Order::where('shipping_country', 'AE')->count());
        $this->command->info('   - Others: ' . Order::whereNotIn('shipping_country', ['KW', 'SA', 'AE'])->count());
        $this->command->info('');
        $this->command->info('🌐 View orders at: http://127.0.0.1:8000/orders');
        $this->command->info('════════════════════════════════════════════════════════════ 🎉');
        $this->command->info('');
    }
}

