<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductCostCalculator;
use App\Services\ShippingCalculator;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        return view('orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'status' => 'required|in:pending,on-hold,in-production,completed,cancelled,delivered',
            'payment_status' => 'required|in:pending,partial,paid,overdue',
            'payment_method' => 'nullable|in:cash,credit,bank_transfer,card',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'nullable|numeric|min:0',
            'shipping_address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Generate order number
        $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(Order::count() + 1, 6, '0', STR_PAD_LEFT);

        // Calculate totals
        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            // If no unit_price provided, get it from product
            $unitPrice = $item['unit_price'] ?? Product::find($item['product_id'])->price;
            $totalAmount += $item['quantity'] * $unitPrice;
        }

        // Create order
        $order = Order::create([
            'customer_id' => $validated['customer_id'],
            'order_number' => $orderNumber,
            'order_date' => $validated['order_date'],
            'total_amount' => $totalAmount,
            'final_amount' => $totalAmount,
            'status' => $validated['status'],
            'payment_status' => $validated['payment_status'],
            'payment_type' => $validated['payment_method'] ?? null,
            'shipping_address' => $validated['shipping_address'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id() ?? 1,
        ]);

        // Create order items
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $unitPrice = $item['unit_price'] ?? $product->price;
            
            $order->orderItems()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $unitPrice,
                'total_price' => $item['quantity'] * $unitPrice,
            ]);
        }

        // Calculate costs and shipping
        $order->recalculateCosts();

        return redirect()->route('orders.show', $order)->with('success', 'Order created successfully');
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'orderItems.product', 'invoices']);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $customers = Customer::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        return view('orders.edit', compact('order', 'customers', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,on-hold,in-production,completed,cancelled,delivered',
            'payment_status' => 'required|in:pending,partial,paid,overdue',
            'payment_method' => 'nullable|in:cash,credit,bank_transfer,card',
            'delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Map payment_method to payment_type for database
        if (isset($validated['payment_method'])) {
            $validated['payment_type'] = $validated['payment_method'];
            unset($validated['payment_method']);
        }

        $order->update($validated);

        return redirect()->route('orders.show', $order)->with('success', 'Order updated successfully');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully');
    }

    /**
     * Update order priority only
     */
    public function updatePriority(Request $request, Order $order)
    {
        $validated = $request->validate([
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        $order->update(['priority' => $validated['priority']]);

        return back()->with('success', 'Order priority updated successfully');
    }

    /**
     * Recalculate order costs
     */
    public function recalculateCosts(Order $order)
    {
        try {
            $order->recalculateCosts();
            return back()->with('success', 'Order costs recalculated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to recalculate costs: ' . $e->getMessage());
        }
    }

    /**
     * View cost breakdown
     */
    public function costBreakdown(Order $order)
    {
        $order->load(['customer', 'orderItems.product']);
        
        $breakdown = [
            'material_cost' => $order->material_cost ?? 0,
            'labor_cost' => $order->labor_cost ?? 0,
            'overhead_cost' => $order->overhead_cost ?? 0,
            'shipping_cost' => $order->shipping_cost ?? 0,
            'total_cost' => $order->total_cost ?? 0,
            'revenue' => $order->final_amount,
            'profit' => $order->profit_amount,
            'profit_margin' => $order->profit_margin ?? 0,
        ];

        return view('orders.cost-breakdown', compact('order', 'breakdown'));
    }

    /**
     * Sync from WooCommerce manually
     */
    public function syncFromWooCommerce()
    {
        try {
            \Artisan::call('woocommerce:sync');
            return back()->with('success', 'WooCommerce sync completed successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Sync failed: ' . $e->getMessage());
        }
    }
}
