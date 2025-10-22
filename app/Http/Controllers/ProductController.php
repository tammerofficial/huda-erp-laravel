<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\BillOfMaterial;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(15);
        return view('products.index', compact('products'));
    }


    public function show(Product $product)
    {
        $product->load('billOfMaterials.bomItems.material');
        return view('products.show', compact('product'));
    }


    public function createBOM(Product $product)
    {
        $materials = Material::where('is_active', true)->get();
        return view('products.bom.create', compact('product', 'materials'));
    }

    public function storeBOM(Request $request, Product $product)
    {
        $request->validate([
            'version' => 'required|string',
            'description' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:materials,id',
            'items.*.quantity' => 'required|numeric|min:0.001',
            'items.*.unit' => 'required|string',
        ]);

        $bom = BillOfMaterial::create([
            'product_id' => $product->id,
            'version' => $request->version,
            'description' => $request->description,
            'status' => 'draft',
        ]);

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

        $bom->update(['total_cost' => $totalCost]);
        return redirect()->route('products.show', $product)->with('success', 'BOM created successfully');
    }

}
