<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="card">
        <div class="card-body text-center">
            <p class="text-sm mb-1" style="color: #666;">Production Orders</p>
            <p class="text-4xl font-bold" style="color: #1a1a1a;">{{ $data['total_production_orders'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Completed</p>
            <p class="text-4xl font-bold">{{ $data['completed_productions'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">In Progress</p>
            <p class="text-4xl font-bold">{{ $data['in_progress'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Production Cost</p>
            <p class="text-3xl font-bold">{{ number_format($data['total_production_cost'], 0) }} KWD</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="card">
        <div class="card-header">
            <h4 class="text-xl font-bold flex items-center" style="color: #1a1a1a;">
                <i class="fas fa-industry mr-2" style="color: #d4af37;"></i>
                Top Produced Products
            </h4>
        </div>
        <div class="card-body">
            <div class="space-y-3">
                @foreach($data['by_product'] as $product)
                <div class="flex justify-between items-center p-4 rounded-lg border" style="border-color: #e5e5e5; background: #fafafa;">
                    <div>
                        <p class="font-semibold" style="color: #1a1a1a;">{{ $product->name }}</p>
                        <p class="text-sm" style="color: #666;">{{ $product->total_quantity }} units</p>
                    </div>
                    <span class="text-lg font-bold" style="color: #ef4444;">{{ number_format($product->total_cost, 0) }} KWD</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="text-xl font-bold flex items-center" style="color: #1a1a1a;">
                <i class="fas fa-list-alt mr-2" style="color: #d4af37;"></i>
                Bill of Materials (BOM)
            </h4>
        </div>
        <div class="card-body">
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 rounded-lg border" style="border-color: #e5e5e5; background: #fafafa;">
                    <span style="color: #1a1a1a;">Total BOMs</span>
                    <span class="text-2xl font-bold" style="color: #3b82f6;">{{ $bomData['total_boms'] }}</span>
                </div>
                <div class="flex justify-between items-center p-4 rounded-lg border" style="border-color: #e5e5e5; background: #fafafa;">
                    <span style="color: #1a1a1a;">Active BOMs</span>
                    <span class="text-2xl font-bold" style="color: #10b981;">{{ $bomData['active_boms'] }}</span>
                </div>
                <div class="flex justify-between items-center p-4 rounded-lg border" style="border-color: #e5e5e5; background: #fafafa;">
                    <span style="color: #1a1a1a;">Products with BOM</span>
                    <span class="text-2xl font-bold" style="color: #8b5cf6;">{{ $bomData['products_with_bom'] }}</span>
                </div>
                <div class="flex justify-between items-center p-4 rounded-lg border" style="border-color: #e5e5e5; background: #fafafa;">
                    <span style="color: #1a1a1a;">Avg Materials per BOM</span>
                    <span class="text-2xl font-bold" style="color: #f59e0b;">{{ number_format($bomData['average_materials_per_bom'] ?? 0, 1) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>