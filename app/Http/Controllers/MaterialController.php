<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Supplier;
use App\Models\MaterialInventory;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::with('supplier', 'inventories')->paginate(15);
        return view('materials.index', compact('materials'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        return view('materials.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:materials',
            'unit' => 'required|string',
            'unit_cost' => 'required|numeric|min:0',
            'category' => 'nullable|string',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'color' => 'nullable|string',
            'size' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'reorder_level' => 'integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('materials', 'public');
        }

        $material = Material::create($data);
        return redirect()->route('materials.index')->with('success', 'Material created successfully');
    }

    public function show(Material $material)
    {
        $material->load(['supplier', 'inventories.warehouse', 'bomItems.billOfMaterial.product']);
        return view('materials.show', compact('material'));
    }

    public function edit(Material $material)
    {
        $suppliers = Supplier::where('is_active', true)->get();
        return view('materials.edit', compact('material', 'suppliers'));
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:materials,sku,' . $material->id,
            'unit' => 'required|string',
            'unit_cost' => 'required|numeric|min:0',
            'category' => 'nullable|string',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'color' => 'nullable|string',
            'size' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'reorder_level' => 'integer|min:0',
            'max_stock' => 'nullable|integer|min:0',
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            if ($material->image_url) {
                Storage::disk('public')->delete($material->image_url);
            }
            $data['image_url'] = $request->file('image')->store('materials', 'public');
        }

        $material->update($data);
        return redirect()->route('materials.index')->with('success', 'Material updated successfully');
    }

    public function destroy(Material $material)
    {
        if ($material->image_url) {
            Storage::disk('public')->delete($material->image_url);
        }
        $material->delete();
        return redirect()->route('materials.index')->with('success', 'Material deleted successfully');
    }

    public function showAdjustInventoryForm(Material $material)
    {
        $warehouses = \App\Models\Warehouse::where('is_active', true)->get();
        return view('materials.adjust-inventory', compact('material', 'warehouses'));
    }

    public function adjustInventory(Request $request, Material $material)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer',
            'movement_type' => 'required|in:in,out,transfer,adjustment',
            'notes' => 'nullable|string',
        ]);

        $inventory = MaterialInventory::where('material_id', $material->id)
            ->where('warehouse_id', $request->warehouse_id)
            ->first();

        if (!$inventory) {
            $inventory = MaterialInventory::create([
                'material_id' => $material->id,
                'warehouse_id' => $request->warehouse_id,
                'quantity' => 0,
            ]);
        }

        $oldQuantity = $inventory->quantity;
        
        if ($request->movement_type === 'in') {
            $inventory->quantity += $request->quantity;
        } elseif ($request->movement_type === 'out') {
            $inventory->quantity -= $request->quantity;
        } else {
            $inventory->quantity = $request->quantity;
        }

        $inventory->save();

        InventoryMovement::create([
            'material_id' => $material->id,
            'warehouse_id' => $request->warehouse_id,
            'movement_type' => $request->movement_type,
            'quantity' => $request->quantity,
            'created_by' => auth()->id(),
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Inventory adjusted successfully');
    }

    /**
     * Show low stock materials
     */
    public function lowStock()
    {
        $materials = Material::where('is_active', true)
            ->with(['supplier', 'inventories'])
            ->get()
            ->filter(function ($material) {
                return $material->isLowStock();
            });

        return view('materials.low-stock', compact('materials'));
    }
}
