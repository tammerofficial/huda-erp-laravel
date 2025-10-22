<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\ProductionOrder;
use App\Models\PurchaseOrder;
use App\Models\Payroll;
use App\Models\WooCommerceSale;
use App\Models\PaymentTransaction;
use App\Models\PaymentGateway;
use Carbon\Carbon;

class AdvancedDashboardSeeder extends Seeder
{
    public function run(): void
    {
        // Create diverse order statuses
        $this->createDiverseOrders();
        
        // Create invoices with different statuses
        $this->createInvoices();
        
        // Create payment transactions
        $this->createPaymentTransactions();
        
        // Create more WooCommerce sales
        $this->createWooCommerceSales();
        
        $this->command->info('Advanced Dashboard data seeded successfully!');
    }

    private function createDiverseOrders()
    {
        $customers = Customer::limit(10)->get();
        $products = Product::limit(5)->get();
        
        if ($customers->count() == 0 || $products->count() == 0) {
            return;
        }

        $statuses = ['pending', 'on-hold', 'in-production', 'completed', 'cancelled', 'delivered'];
        $dates = [
            Carbon::now()->subDays(30),
            Carbon::now()->subDays(20),
            Carbon::now()->subDays(10),
            Carbon::now()->subDays(5),
            Carbon::now()->subDays(1),
        ];

        foreach ($dates as $date) {
            for ($i = 0; $i < 3; $i++) {
                $status = $statuses[array_rand($statuses)];
                $customer = $customers->random();
                $product = $products->random();
                
                $totalAmount = rand(100, 2000);
                $orderNumber = 'ORD-' . str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
                
                // Check if order number already exists
                while (Order::where('order_number', $orderNumber)->exists()) {
                    $orderNumber = 'ORD-' . str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT);
                }
                
                Order::create([
                    'customer_id' => $customer->id,
                    'order_number' => $orderNumber,
                    'order_date' => $date,
                    'status' => $status,
                    'total_amount' => $totalAmount,
                    'final_amount' => $totalAmount,
                    'notes' => 'Sample order for dashboard',
                ]);
            }
        }
    }

    private function createInvoices()
    {
        $orders = Order::limit(10)->get();
        
        if ($orders->count() == 0) {
            return;
        }

        $statuses = ['draft', 'sent', 'paid', 'overdue', 'cancelled'];
        
        foreach ($orders as $order) {
            $totalAmount = $order->total_amount;
            $taxAmount = $totalAmount * 0.15; // 15% tax
            $discountAmount = $totalAmount * 0.05; // 5% discount
            $finalAmount = $totalAmount + $taxAmount - $discountAmount;
            
            Invoice::create([
                'order_id' => $order->id,
                'invoice_number' => 'INV-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'invoice_date' => $order->order_date,
                'due_date' => $order->order_date->addDays(30),
                'status' => $statuses[array_rand($statuses)],
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
                'payment_status' => 'pending',
                'payment_method' => 'bank_transfer',
                'notes' => 'Sample invoice',
                'created_by' => 1,
            ]);
        }
    }

    private function createPaymentTransactions()
    {
        // Create payment gateways first
        $gateways = [
            ['name' => 'KNET', 'type' => 'bank_transfer', 'provider' => 'knet', 'is_active' => true],
            ['name' => 'Visa/Mastercard', 'type' => 'credit_card', 'provider' => 'stripe', 'is_active' => true],
            ['name' => 'PayPal', 'type' => 'digital_wallet', 'provider' => 'paypal', 'is_active' => true],
        ];

        foreach ($gateways as $gateway) {
            PaymentGateway::firstOrCreate(
                ['name' => $gateway['name']],
                $gateway
            );
        }

        $gateways = PaymentGateway::all();
        $orders = Order::limit(15)->get();
        
        if ($gateways->count() == 0 || $orders->count() == 0) {
            return;
        }

        foreach ($orders as $order) {
            $gateway = $gateways->random();
            $amount = $order->total_amount;
            $fee = $amount * 0.03; // 3% fee
            
            PaymentTransaction::create([
                'payment_gateway_id' => $gateway->id,
                'transaction_id' => 'TXN-' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
                'payable_type' => 'App\Models\Order',
                'payable_id' => $order->id,
                'amount' => $amount,
                'fee' => $fee,
                'net_amount' => $amount - $fee,
                'currency' => 'KWD',
                'status' => 'completed',
                'reference_number' => 'REF-' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
                'processed_at' => $order->order_date,
                'created_at' => $order->order_date,
            ]);
        }
    }

    private function createWooCommerceSales()
    {
        $statuses = ['completed', 'processing', 'pending', 'cancelled'];
        $paymentMethods = ['credit_card', 'bank_transfer', 'cash_on_delivery'];
        
        // Get existing orders to link to
        $existingOrders = Order::limit(10)->get();
        
        for ($i = 0; $i < 10; $i++) {
            $total = rand(50, 500);
            $subtotal = $total * 0.9;
            $tax = $total * 0.1;
            $shipping = rand(5, 25);
            $discount = rand(0, 20);
            $productionCost = $total * 0.6;
            $profit = $total - $productionCost;
            
            WooCommerceSale::create([
                'wc_order_id' => 'WC-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'order_id' => $existingOrders->count() > 0 ? $existingOrders->random()->id : null,
                'customer_name' => 'Customer ' . ($i + 1),
                'customer_email' => 'customer' . ($i + 1) . '@example.com',
                'order_date' => Carbon::now()->subDays(rand(1, 30)),
                'status' => $statuses[array_rand($statuses)],
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'discount' => $discount,
                'total' => $total,
                'production_cost' => $productionCost,
                'profit' => $profit,
                'items' => json_encode([
                    ['name' => 'Product ' . ($i + 1), 'quantity' => 1, 'price' => $subtotal]
                ]),
            ]);
        }
    }
}