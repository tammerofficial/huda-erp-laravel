<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QualityCheck;
use App\Models\ProductionOrder;
use App\Models\Employee;
use App\Models\Product;

class QualityCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $qualityChecks = QualityCheck::with(['productionOrder', 'product', 'inspector'])
            ->orderBy('inspection_date', 'desc')
            ->paginate(20);
            
        return view('quality-checks.index', compact('qualityChecks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productionOrders = ProductionOrder::where('status', 'awaiting_quality_check')->get();
        $inspectors = Employee::where('employment_status', 'active')
            ->whereJsonContains('skills', 'quality_inspection')
            ->get();
        
        return view('quality-checks.create', compact('productionOrders', 'inspectors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'product_id' => 'required|exists:products,id',
            'inspector_id' => 'required|exists:employees,id',
            'status' => 'required|in:passed,failed',
            'items_checked' => 'required|integer|min:1',
            'items_passed' => 'required|integer|min:0',
            'items_failed' => 'required|integer|min:0',
            'defects' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $validated['inspection_date'] = now();
        $qualityCheck = QualityCheck::create($validated);

        // Update production order status
        $productionOrder = ProductionOrder::find($validated['production_order_id']);
        if ($validated['status'] === 'passed') {
            $productionOrder->update(['status' => 'quality_approved']);
        } else {
            $productionOrder->update(['status' => 'quality_rejected']);
        }

        return redirect()->route('quality-checks.index')
            ->with('success', 'تم تسجيل فحص الجودة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(QualityCheck $qualityCheck)
    {
        $qualityCheck->load(['productionOrder', 'product', 'inspector']);
        return view('quality-checks.show', compact('qualityCheck'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QualityCheck $qualityCheck)
    {
        $productionOrders = ProductionOrder::where('status', 'awaiting_quality_check')->get();
        $inspectors = Employee::where('employment_status', 'active')
            ->whereJsonContains('skills', 'quality_inspection')
            ->get();
        
        return view('quality-checks.edit', compact('qualityCheck', 'productionOrders', 'inspectors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QualityCheck $qualityCheck)
    {
        $validated = $request->validate([
            'production_order_id' => 'required|exists:production_orders,id',
            'product_id' => 'required|exists:products,id',
            'inspector_id' => 'required|exists:employees,id',
            'status' => 'required|in:passed,failed',
            'items_checked' => 'required|integer|min:1',
            'items_passed' => 'required|integer|min:0',
            'items_failed' => 'required|integer|min:0',
            'defects' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $qualityCheck->update($validated);

        return redirect()->route('quality-checks.index')
            ->with('success', 'تم تحديث فحص الجودة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QualityCheck $qualityCheck)
    {
        $qualityCheck->delete();
        
        return redirect()->route('quality-checks.index')
            ->with('success', 'تم حذف فحص الجودة بنجاح');
    }

    /**
     * Inspect a production order
     */
    public function inspect(ProductionOrder $productionOrder)
    {
        $inspectors = Employee::where('employment_status', 'active')
            ->whereJsonContains('skills', 'quality_inspection')
            ->get();
        
        return view('quality-checks.inspect', compact('productionOrder', 'inspectors'));
    }

    /**
     * Submit inspection results
     */
    public function submitInspection(Request $request, ProductionOrder $productionOrder)
    {
        $validated = $request->validate([
            'inspector_id' => 'required|exists:employees,id',
            'status' => 'required|in:passed,failed',
            'items_checked' => 'required|integer|min:1',
            'items_passed' => 'required|integer|min:0',
            'items_failed' => 'required|integer|min:0',
            'defects' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);

        $qualityCheck = QualityCheck::create([
            'production_order_id' => $productionOrder->id,
            'product_id' => $productionOrder->product_id,
            'inspector_id' => $validated['inspector_id'],
            'inspection_date' => now(),
            ...$validated
        ]);

        // Update production order status
        if ($validated['status'] === 'passed') {
            $productionOrder->update(['status' => 'quality_approved']);
        } else {
            $productionOrder->update(['status' => 'quality_rejected']);
        }

        return redirect()->route('quality-checks.index')
            ->with('success', 'تم تسجيل نتائج فحص الجودة بنجاح');
    }
}
