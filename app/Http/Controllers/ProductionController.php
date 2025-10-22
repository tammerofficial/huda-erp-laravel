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

    /**
     * Workshop Dashboard - Live monitoring
     */
    public function dashboard()
    {
        // Get all active employees with their current tasks
        $employees = Employee::where('employment_status', 'active')
            ->whereIn('department', ['Cutting', 'Sewing', 'Embroidery', 'Quality Assurance', 'Finishing'])
            ->with(['user'])
            ->get()
            ->map(function ($employee) {
                // Get current task (in-progress stage assigned to this employee)
                $currentTask = ProductionStage::where('employee_id', $employee->id)
                    ->where('status', 'in-progress')
                    ->with(['productionOrder.product', 'productionOrder.order'])
                    ->first();

                // Get pending tasks (pending stages assigned to this employee)
                $pendingTasks = ProductionStage::where('employee_id', $employee->id)
                    ->where('status', 'pending')
                    ->with(['productionOrder.product', 'productionOrder.order'])
                    ->get();

                // Get employee stats
                $stats = [
                    'today' => ProductionStage::where('employee_id', $employee->id)
                        ->where('status', 'completed')
                        ->whereDate('end_time', today())
                        ->count(),
                    'week' => ProductionStage::where('employee_id', $employee->id)
                        ->where('status', 'completed')
                        ->whereBetween('end_time', [now()->startOfWeek(), now()->endOfWeek()])
                        ->count(),
                    'total' => ProductionStage::where('employee_id', $employee->id)
                        ->where('status', 'completed')
                        ->count(),
                ];

                return [
                    'id' => $employee->id,
                    'name' => $employee->user->name,
                    'employee_id' => $employee->employee_id,
                    'position' => $employee->position,
                    'department' => $employee->department,
                    'initials' => strtoupper(substr($employee->user->name, 0, 2)),
                    'current_task' => $currentTask ? [
                        'stage_id' => $currentTask->id,
                        'production_number' => $currentTask->productionOrder->production_number,
                        'product_name' => $currentTask->productionOrder->product->name,
                        'stage' => ucfirst(str_replace('_', ' ', $currentTask->stage_name)),
                        'started_at' => $currentTask->start_time->format('H:i'),
                    ] : null,
                    'tasks' => $pendingTasks->map(function ($task) {
                        return [
                            'id' => $task->id,
                            'production_number' => $task->productionOrder->production_number,
                            'product_name' => $task->productionOrder->product->name,
                            'stage' => ucfirst(str_replace('_', ' ', $task->stage_name)),
                        ];
                    }),
                    'stats' => $stats,
                ];
            });

        // Get all pending stages (not assigned yet)
        $pendingStages = ProductionStage::where('status', 'pending')
            ->whereNull('employee_id')
            ->with(['productionOrder.product', 'productionOrder.order.customer'])
            ->get()
            ->map(function ($stage) {
                return [
                    'id' => $stage->id,
                    'production_number' => $stage->productionOrder->production_number,
                    'product_name' => $stage->productionOrder->product->name,
                    'customer_name' => $stage->productionOrder->order->customer->name,
                    'stage_name' => $stage->stage_name,
                    'priority' => $stage->productionOrder->priority ?? 'normal',
                ];
            });

        // Calculate stats
        $stats = [
            'activeOrders' => ProductionOrder::whereIn('status', ['pending', 'in-progress'])->count(),
            'activeEmployees' => Employee::where('employment_status', 'active')
                ->whereIn('department', ['Cutting', 'Sewing', 'Embroidery', 'Quality Assurance', 'Finishing'])
                ->count(),
            'inProgressStages' => ProductionStage::where('status', 'in-progress')->count(),
            'completedToday' => ProductionStage::where('status', 'completed')
                ->whereDate('end_time', today())
                ->count(),
        ];

        return view('production.dashboard', compact('employees', 'pendingStages', 'stats'));
    }

    /**
     * Get dashboard data (AJAX)
     */
    public function getDashboardData()
    {
        // Same logic as dashboard() but return JSON
        $dashboardController = new self();
        $view = $dashboardController->dashboard();
        $data = $view->getData();

        return response()->json([
            'employees' => $data['employees'],
            'pendingStages' => $data['pendingStages'],
            'stats' => $data['stats'],
        ]);
    }

    /**
     * Assign stage to employee
     */
    public function assignStage(Request $request, ProductionStage $stage)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $stage->update([
            'employee_id' => $request->employee_id,
        ]);

        return response()->json(['success' => true]);
    }

    public function create()
    {
        $orders = Order::where('status', 'on-hold')
            ->with(['customer', 'orderItems'])
            ->orderBy('delivery_date', 'asc')
            ->get();
        $products = Product::where('is_active', true)->get();
        return view('production.create', compact('orders', 'products'));
    }

    /**
     * Get order details with products for auto-fill
     */
    public function getOrderDetails($orderId)
    {
        $order = Order::with(['orderItems.product', 'customer'])->findOrFail($orderId);
        
        return response()->json([
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'delivery_date' => $order->delivery_date?->format('Y-m-d'),
                'priority' => $order->priority ?? 'normal',
                'notes' => $order->notes,
            ],
            'customer' => [
                'name' => $order->customer->name,
            ],
            'products' => $order->orderItems->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'size' => $item->size,
                    'color' => $item->color,
                    'notes' => $item->notes,
                    'estimated_cost' => $item->product->estimated_cost ?? ($item->product->cost_per_unit * $item->quantity),
                ];
            }),
        ]);
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

        return redirect()->route('productions.index')->with('success', 'Production order created successfully');
    }

    public function show(ProductionOrder $production)
    {
        $production->load(['order.customer', 'product', 'productionStages.employee']);
        $employees = Employee::where('employment_status', 'active')->get();
        return view('production.show', compact('production', 'employees'));
    }

    public function edit(ProductionOrder $production)
    {
        $orders = Order::where('status', 'on-hold')->with('customer')->get();
        $products = Product::where('is_active', true)->get();
        return view('production.edit', compact('production', 'orders', 'products'));
    }

    public function update(Request $request, ProductionOrder $production)
    {
        $request->validate([
            'status' => 'required|in:pending,in-progress,completed,cancelled,on-hold',
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        $production->update($request->all());
        return redirect()->route('productions.index')->with('success', 'Production order updated successfully');
    }

    public function destroy(ProductionOrder $production)
    {
        $production->delete();
        return redirect()->route('productions.index')->with('success', 'Production order deleted successfully');
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
