<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="card">
        <div class="card-body text-center">
            <p class="text-sm mb-1" style="color: #666;">Total Warehouses</p>
            <p class="text-4xl font-bold" style="color: #1a1a1a;">{{ $data['total_warehouses'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Total Capacity</p>
            <p class="text-4xl font-bold">{{ number_format($data['total_capacity'], 0) }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Current Stock</p>
            <p class="text-4xl font-bold">{{ number_format($data['total_current_stock'], 0) }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #d4af37 0%, #c9a84a 100%);">
        <div class="card-body text-center" style="color: #1a1a1a;">
            <p class="text-sm mb-1 opacity-80">Utilization Rate</p>
            <p class="text-4xl font-bold">{{ number_format($data['utilization_rate'], 1) }}%</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4 class="text-xl font-bold flex items-center" style="color: #1a1a1a;">
            <i class="fas fa-warehouse mr-2" style="color: #d4af37;"></i>
            Warehouses Details
        </h4>
    </div>
    <div class="card-body">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($data['by_warehouse'] as $warehouse)
            <div class="rounded-lg p-4 border" style="border-color: #e5e5e5; background: #fafafa;">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h5 class="font-bold text-lg" style="color: #1a1a1a;">{{ $warehouse->name }}</h5>
                        <p class="text-sm" style="color: #666;">📍 {{ $warehouse->location }}</p>
                        @if($warehouse->manager)
                            <p class="text-xs" style="color: #999;">👤 {{ $warehouse->manager->user->name }}</p>
                        @endif
                    </div>
                    <span class="text-2xl">🏢</span>
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span style="color: #666;">Capacity:</span>
                        <span class="font-semibold" style="color: #1a1a1a;">{{ number_format($warehouse->capacity, 0) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span style="color: #666;">Current Stock:</span>
                        <span class="font-semibold" style="color: #1a1a1a;">{{ number_format($warehouse->current_stock, 0) }}</span>
                    </div>
                    
                    <div class="mt-3">
                        <div class="flex justify-between text-xs mb-1">
                            <span style="color: #666;">Utilization Rate</span>
                            <span class="font-bold" style="color: #1a1a1a;">{{ number_format($warehouse->utilization, 1) }}%</span>
                        </div>
                        <div class="w-full rounded-full h-2" style="background: #e5e5e5;">
                            <div class="h-2 rounded-full" 
                                 style="width: {{ min($warehouse->utilization, 100) }}%; background: linear-gradient(to right, #d4af37, #c9a84a);"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
