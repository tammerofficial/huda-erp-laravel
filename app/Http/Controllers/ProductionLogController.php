<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionLog;
use App\Models\Employee;
use App\Models\ProductionStage;
use App\Models\Product;

class ProductionLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productionLogs = ProductionLog::with(['employee', 'productionStage', 'product'])
            ->orderBy('start_time', 'desc')
            ->paginate(20);
            
        return view('production-logs.index', compact('productionLogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('employment_status', 'active')->get();
        $stages = ProductionStage::with('productionOrder')->get();
        $products = Product::all();
        
        return view('production-logs.create', compact('employees', 'stages', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'production_stage_id' => 'required|exists:production_stages,id',
            'product_id' => 'required|exists:products,id',
            'pieces_completed' => 'required|integer|min:1',
            'rate_per_piece' => 'nullable|numeric|min:0',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'expected_duration' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        ProductionLog::create($validated);

        return redirect()->route('production-logs.index')
            ->with('success', 'تم تسجيل سجل الإنتاج بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductionLog $productionLog)
    {
        $productionLog->load(['employee', 'productionStage', 'product']);
        return view('production-logs.show', compact('productionLog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductionLog $productionLog)
    {
        $employees = Employee::where('employment_status', 'active')->get();
        $stages = ProductionStage::with('productionOrder')->get();
        $products = Product::all();
        
        return view('production-logs.edit', compact('productionLog', 'employees', 'stages', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductionLog $productionLog)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'production_stage_id' => 'required|exists:production_stages,id',
            'product_id' => 'required|exists:products,id',
            'pieces_completed' => 'required|integer|min:1',
            'rate_per_piece' => 'nullable|numeric|min:0',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'expected_duration' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $productionLog->update($validated);

        return redirect()->route('production-logs.index')
            ->with('success', 'تم تحديث سجل الإنتاج بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductionLog $productionLog)
    {
        $productionLog->delete();
        
        return redirect()->route('production-logs.index')
            ->with('success', 'تم حذف سجل الإنتاج بنجاح');
    }

    /**
     * Complete a production log
     */
    public function complete(ProductionLog $log)
    {
        $log->update([
            'end_time' => now(),
            'quality_status' => 'pending'
        ]);

        return redirect()->route('production-logs.index')
            ->with('success', 'تم إكمال سجل الإنتاج');
    }

    /**
     * Approve quality for a production log
     */
    public function approve(ProductionLog $log)
    {
        $log->update(['quality_status' => 'approved']);

        return redirect()->route('production-logs.index')
            ->with('success', 'تم قبول جودة الإنتاج');
    }

    /**
     * Reject quality for a production log
     */
    public function reject(ProductionLog $log)
    {
        $log->update(['quality_status' => 'rejected']);

        return redirect()->route('production-logs.index')
            ->with('success', 'تم رفض جودة الإنتاج');
    }
}
