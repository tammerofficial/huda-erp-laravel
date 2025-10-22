<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="card">
        <div class="card-body text-center">
            <p class="text-sm mb-1" style="color: #666;">Total Materials</p>
            <p class="text-4xl font-bold" style="color: #1a1a1a;">{{ $data['total_materials'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Inventory Value</p>
            <p class="text-3xl font-bold">{{ number_format($data['total_stock_value'], 0) }} KWD</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Low Stock</p>
            <p class="text-4xl font-bold">{{ $data['low_stock_materials'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Out of Stock</p>
            <p class="text-4xl font-bold">{{ $data['out_of_stock'] }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card">
        <div class="card-header">
            <h4 class="text-xl font-bold flex items-center" style="color: #1a1a1a;">
                <i class="fas fa-trophy mr-2" style="color: #d4af37;"></i>
                Top Used Materials
            </h4>
        </div>
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="text-left">
                            <th class="pb-3">Material</th>
                            <th class="pb-3 text-center">Required</th>
                            <th class="pb-3 text-right">In Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['top_used'] as $material)
                        <tr>
                            <td class="py-3">
                                <p class="font-semibold" style="color: #1a1a1a;">{{ $material->name }}</p>
                                <p class="text-sm" style="color: #666;">{{ $material->sku }}</p>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge badge-danger">{{ $material->total_required }}</span>
                            </td>
                            <td class="py-3 text-right">
                                <span class="badge badge-success">{{ $material->current_stock ?? 0 }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="text-xl font-bold flex items-center" style="color: #1a1a1a;">
                <i class="fas fa-layer-group mr-2" style="color: #d4af37;"></i>
                Materials by Category
            </h4>
        </div>
        <div class="card-body">
            <div class="space-y-3">
                @foreach($data['by_category'] as $category)
                <div class="p-4 rounded-lg border" style="border-color: #e5e5e5; background: #fafafa;">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-semibold" style="color: #1a1a1a;">{{ $category->category ?: 'Uncategorized' }}</span>
                        <span class="font-bold" style="color: #3b82f6;">{{ $category->count }} items</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span style="color: #666;">Stock: {{ $category->total_stock }}</span>
                        <span style="color: #10b981; font-weight: 600;">Value: {{ number_format($category->total_value, 0) }} KWD</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
