<?php

namespace App\Http\Controllers;

use App\Models\ProductionOrder;
use App\Models\ProductionStage;
use App\Models\Order;
use App\Models\Product;
use App\Models\Employee;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = ProductionOrder::with(['order.customer', 'product', 'productionStages'])->paginate(15);
        return view('production.index', compact('productions'));
    }

    public function create()
    {
        $orders = Order::where('status', 'on-hold')->with('customer')->get();
        $products = Product::where('is_active', true)->get();
        return view('production.create', compact('orders', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'due_date' => 'required|date|after:today',
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        $productionOrder = ProductionOrder::create([
            'order_id' => $request->order_id,
            'product_id' => $request->product_id,
            'production_number' => 'PROD' . time(),
            'quantity' => $request->quantity,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'created_by' => auth()->id(),
        ]);

        // Create production stages
        $stages = ['cutting', 'sewing', 'embroidery', 'ironing', 'quality_check'];
        foreach ($stages as $index => $stage) {
            ProductionStage::create([
                'production_order_id' => $productionOrder->id,
                'stage_name' => $stage,
                'sequence_order' => $index + 1,
            ]);
        }

        return redirect()->route('production.index')->with('success', 'Production order created successfully');
    }

    public function show(ProductionOrder $productionOrder)
    {
        $productionOrder->load(['order.customer', 'product', 'productionStages.employee']);
        $employees = Employee::where('employment_status', 'active')->get();
        return view('production.show', compact('productionOrder', 'employees'));
    }

    public function edit(ProductionOrder $productionOrder)
    {
        $orders = Order::where('status', 'on-hold')->with('customer')->get();
        $products = Product::where('is_active', true)->get();
        return view('production.edit', compact('productionOrder', 'orders', 'products'));
    }

    public function update(Request $request, ProductionOrder $productionOrder)
    {
        $request->validate([
            'status' => 'required|in:pending,in-progress,completed,cancelled,on-hold',
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        $productionOrder->update($request->all());
        return redirect()->route('production.index')->with('success', 'Production order updated successfully');
    }

    public function destroy(ProductionOrder $productionOrder)
    {
        $productionOrder->delete();
        return redirect()->route('production.index')->with('success', 'Production order deleted successfully');
    }

    public function startStage(Request $request, ProductionStage $stage)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $stage->update([
            'status' => 'in-progress',
            'start_time' => now(),
            'employee_id' => $request->employee_id,
        ]);

        return redirect()->back()->with('success', 'Stage started successfully');
    }

    public function completeStage(ProductionStage $stage)
    {
        $stage->update([
            'status' => 'completed',
            'end_time' => now(),
            'duration_minutes' => $stage->start_time ? $stage->start_time->diffInMinutes(now()) : 0,
        ]);

        return redirect()->back()->with('success', 'Stage completed successfully');
    }
}
