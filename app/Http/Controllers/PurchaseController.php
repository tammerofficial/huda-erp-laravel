<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Material;
use App\Models\MaterialInventory;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['supplier', 'items.material'])->paginate(15);
        return view('purchases.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $materials = Material::where('is_active', true)->get();
        return view('purchases.create', compact('suppliers', 'materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date|after:order_date',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'payment_terms' => 'nullable|string',
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        $purchaseOrder = PurchaseOrder::create([
            'supplier_id' => $request->supplier_id,
            'order_number' => 'PO' . time(),
            'order_date' => $request->order_date,
            'delivery_date' => $request->delivery_date,
            'total_amount' => 0,
            'final_amount' => 0,
            'payment_terms' => $request->payment_terms,
            'priority' => $request->priority,
            'created_by' => auth()->id(),
        ]);

        $total = 0;
        foreach ($request->items as $item) {
            $itemTotal = $item['quantity'] * $item['unit_price'];
            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'material_id' => $item['material_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $itemTotal,
            ]);
            $total += $itemTotal;
        }

        $purchaseOrder->update([
            'total_amount' => $total,
            'final_amount' => $total,
        ]);

        return redirect()->route('purchases.index')->with('success', 'Purchase order created successfully');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'items.material']);
        return view('purchases.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $suppliers = Supplier::where('is_active', true)->get();
        $materials = Material::where('is_active', true)->get();
        $purchaseOrder->load('items.material');
        return view('purchases.edit', compact('purchaseOrder', 'suppliers', 'materials'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,sent,received,cancelled',
            'payment_status' => 'required|in:pending,partial,paid',
        ]);

        $purchaseOrder->update($request->all());
        return redirect()->route('purchases.index')->with('success', 'Purchase order updated successfully');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
        return redirect()->route('purchases.index')->with('success', 'Purchase order deleted successfully');
    }

    public function receive(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->update(['status' => 'received']);
        
        foreach ($purchaseOrder->items as $item) {
            $inventory = MaterialInventory::where('material_id', $item->material_id)
                ->where('warehouse_id', 1) // Default warehouse
                ->first();

            if (!$inventory) {
                $inventory = MaterialInventory::create([
                    'material_id' => $item->material_id,
                    'warehouse_id' => 1,
                    'quantity' => 0,
                ]);
            }

            $inventory->quantity += $item->quantity;
            $inventory->save();

            InventoryMovement::create([
                'material_id' => $item->material_id,
                'warehouse_id' => 1,
                'movement_type' => 'in',
                'quantity' => $item->quantity,
                'reference_type' => 'purchase_order',
                'reference_id' => $purchaseOrder->id,
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->back()->with('success', 'Purchase order received successfully');
    }
}
