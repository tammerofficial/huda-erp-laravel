<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\MaterialInventory;
use App\Models\InventoryMovement;
use App\Models\Employee;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::with('manager')->paginate(15);
        return view('warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        $managers = Employee::where('employment_status', 'active')->get();
        return view('warehouses.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'capacity' => 'nullable|integer|min:0',
            'manager_id' => 'nullable|exists:employees,id',
            'is_active' => 'boolean',
        ]);

        Warehouse::create($request->all());
        return redirect()->route('warehouses.index')->with('success', 'Warehouse created successfully');
    }

    public function show(Warehouse $warehouse)
    {
        $warehouse->load('manager');
        
        // Get material inventory
        $materialInventory = MaterialInventory::where('warehouse_id', $warehouse->id)
            ->with('material')
            ->limit(10)
            ->get();
        
        // Get recent movements
        $recentMovements = InventoryMovement::where('warehouse_id', $warehouse->id)
            ->with(['material', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Calculate stats
        $totalItems = MaterialInventory::where('warehouse_id', $warehouse->id)->count();
        $lowStockItems = MaterialInventory::where('warehouse_id', $warehouse->id)
            ->whereColumn('quantity', '<=', 'reorder_level')
            ->count();
        $outOfStock = MaterialInventory::where('warehouse_id', $warehouse->id)
            ->where('quantity', 0)
            ->count();
        
        return view('warehouses.show', compact(
            'warehouse', 
            'materialInventory', 
            'recentMovements',
            'totalItems',
            'lowStockItems',
            'outOfStock'
        ));
    }

    public function edit(Warehouse $warehouse)
    {
        $managers = Employee::where('employment_status', 'active')->get();
        return view('warehouses.edit', compact('warehouse', 'managers'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'capacity' => 'nullable|integer|min:0',
            'manager_id' => 'nullable|exists:employees,id',
            'is_active' => 'boolean',
        ]);

        $warehouse->update($request->all());
        return redirect()->route('warehouses.index')->with('success', 'Warehouse updated successfully');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('warehouses.index')->with('success', 'Warehouse deleted successfully');
    }

    public function inventory(Warehouse $warehouse)
    {
        $inventories = MaterialInventory::where('warehouse_id', $warehouse->id)
            ->with('material')
            ->paginate(15);
        return view('warehouses.inventory', compact('warehouse', 'inventories'));
    }

    public function movements(Warehouse $warehouse)
    {
        $movements = InventoryMovement::where('warehouse_id', $warehouse->id)
            ->with(['material', 'creator'])
            ->latest()
            ->paginate(15);
        return view('warehouses.movements', compact('warehouse', 'movements'));
    }

    public function lowStock()
    {
        $lowStockItems = MaterialInventory::whereRaw('quantity <= reorder_level')
            ->with(['material', 'warehouse'])
            ->get();
        return view('warehouses.low-stock', compact('lowStockItems'));
    }
}
