<?php

namespace App\Http\Controllers;

use App\Models\BillOfMaterial;
use App\Models\Product;
use App\Models\Material;
use App\Models\BomItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillOfMaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = BillOfMaterial::with(['product', 'bomItems.material', 'createdBy']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            })->orWhere('version', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $boms = $query->latest()->paginate(20)->withQueryString();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('bom.index', compact('boms', 'products'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $materials = Material::where('is_active', true)->orderBy('name')->get();
        
        return view('bom.create', compact('products', 'materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'version' => 'required|string|max:50',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Create BOM
            $bom = BillOfMaterial::create([
                'product_id' => $request->product_id,
                'version' => $request->version,
                'description' => $request->description,
                'status' => 'draft',
                'created_by' => auth()->id() ?? 1,
            ]);

            // Add BOM Items
            $totalCost = 0;
            foreach ($request->items as $item) {
                $material = Material::find($item['material_id']);
                $itemCost = $item['quantity'] * $material->unit_cost;
                
                $bom->bomItems()->create([
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'unit_cost' => $material->unit_cost,
                    'total_cost' => $itemCost,
                ]);
                
                $totalCost += $itemCost;
            }

            // Update total cost
            $bom->update(['total_cost' => $totalCost]);

            DB::commit();
            return redirect()->route('bom.show', $bom)->with('success', 'BOM created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create BOM: ' . $e->getMessage());
        }
    }

    public function show(BillOfMaterial $billOfMaterial)
    {
        $billOfMaterial->load(['product', 'bomItems.material', 'createdBy']);
        
        return view('bom.show', compact('billOfMaterial'));
    }

    public function edit(BillOfMaterial $billOfMaterial)
    {
        $billOfMaterial->load('bomItems.material');
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $materials = Material::where('is_active', true)->orderBy('name')->get();
        
        // Prepare items for JavaScript
        $bomItemsData = $billOfMaterial->bomItems->map(function($item) {
            return [
                'material_id' => $item->material_id,
                'quantity' => (float) $item->quantity,
                'unit' => $item->unit,
                'unit_cost' => (float) $item->unit_cost,
                'total_cost' => (float) $item->total_cost
            ];
        })->values();
        
        return view('bom.edit', compact('billOfMaterial', 'products', 'materials', 'bomItemsData'));
    }

    public function update(Request $request, BillOfMaterial $billOfMaterial)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'version' => 'required|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,active,inactive',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Update BOM
            $billOfMaterial->update([
                'product_id' => $request->product_id,
                'version' => $request->version,
                'description' => $request->description,
                'status' => $request->status,
            ]);

            // Delete old items
            $billOfMaterial->bomItems()->delete();

            // Add new items
            $totalCost = 0;
            foreach ($request->items as $item) {
                $material = Material::find($item['material_id']);
                $itemCost = $item['quantity'] * $material->unit_cost;
                
                $billOfMaterial->bomItems()->create([
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'unit_cost' => $material->unit_cost,
                    'total_cost' => $itemCost,
                ]);
                
                $totalCost += $itemCost;
            }

            // Update total cost
            $billOfMaterial->update(['total_cost' => $totalCost]);

            // Refresh the model to get updated data
            $billOfMaterial->refresh();

            DB::commit();
            
            return redirect()->route('bom.show', $billOfMaterial)
                ->with('success', 'BOM updated successfully')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update BOM: ' . $e->getMessage());
        }
    }

    public function destroy(BillOfMaterial $billOfMaterial)
    {
        try {
            $billOfMaterial->bomItems()->delete();
            $billOfMaterial->delete();
            
            return redirect()->route('bom.index')->with('success', 'BOM deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete BOM: ' . $e->getMessage());
        }
    }

    public function duplicate(BillOfMaterial $billOfMaterial)
    {
        DB::beginTransaction();
        try {
            // Create new BOM
            $newBom = BillOfMaterial::create([
                'product_id' => $billOfMaterial->product_id,
                'version' => $billOfMaterial->version . ' (Copy)',
                'description' => $billOfMaterial->description,
                'status' => 'draft',
                'total_cost' => $billOfMaterial->total_cost,
                'created_by' => auth()->id() ?? 1,
            ]);

            // Copy items
            foreach ($billOfMaterial->bomItems as $item) {
                $newBom->bomItems()->create([
                    'material_id' => $item->material_id,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'unit_cost' => $item->unit_cost,
                    'total_cost' => $item->total_cost,
                ]);
            }

            DB::commit();
            return redirect()->route('bom.edit', $newBom)->with('success', 'BOM duplicated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to duplicate BOM: ' . $e->getMessage());
        }
    }

    public function activate(BillOfMaterial $billOfMaterial)
    {
        DB::beginTransaction();
        try {
            // Deactivate other BOMs for this product
            BillOfMaterial::where('product_id', $billOfMaterial->product_id)
                ->where('id', '!=', $billOfMaterial->id)
                ->update(['status' => 'inactive', 'is_default' => false]);

            // Activate this BOM
            $billOfMaterial->update([
                'status' => 'active',
                'is_default' => true,
            ]);

            // Refresh to get updated data
            $billOfMaterial->refresh();

            DB::commit();
            
            return redirect()->route('bom.index')
                ->with('success', 'BOM activated successfully')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to activate BOM: ' . $e->getMessage());
        }
    }

    public function bulkCreate(Request $request)
    {
        $request->validate([
            'boms' => 'required|array|min:1',
            'boms.*.product_id' => 'required|exists:products,id',
            'boms.*.version' => 'required|string|max:50',
            'boms.*.items' => 'required|array|min:1',
            'boms.*.items.*.material_id' => 'required|exists:materials,id',
            'boms.*.items.*.quantity' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $created = 0;
            foreach ($request->boms as $bomData) {
                // Create BOM
                $bom = BillOfMaterial::create([
                    'product_id' => $bomData['product_id'],
                    'version' => $bomData['version'],
                    'description' => $bomData['description'] ?? null,
                    'status' => 'draft',
                    'created_by' => auth()->id() ?? 1,
                ]);

                // Add items
                $totalCost = 0;
                foreach ($bomData['items'] as $item) {
                    $material = Material::find($item['material_id']);
                    $itemCost = $item['quantity'] * $material->unit_cost;
                    
                    $bom->bomItems()->create([
                        'material_id' => $item['material_id'],
                        'quantity' => $item['quantity'],
                        'unit' => $item['unit'] ?? $material->unit,
                        'unit_cost' => $material->unit_cost,
                        'total_cost' => $itemCost,
                    ]);
                    
                    $totalCost += $itemCost;
                }

                $bom->update(['total_cost' => $totalCost]);
                $created++;
            }

            DB::commit();
            return redirect()->route('bom.index')->with('success', "Successfully created {$created} BOMs");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create BOMs: ' . $e->getMessage());
        }
    }
}

