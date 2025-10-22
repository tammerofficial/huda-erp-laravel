<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="card">
        <div class="card-body text-center">
            <p class="text-sm mb-1" style="color: #666;">Total Orders</p>
            <p class="text-4xl font-bold" style="color: #1a1a1a;">{{ $data['total_orders'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Total Revenue</p>
            <p class="text-4xl font-bold">{{ number_format($data['total_revenue'], 3) }} KWD</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #d4af37 0%, #c9a84a 100%);">
        <div class="card-body text-center" style="color: #1a1a1a;">
            <p class="text-sm mb-1 opacity-80">Average Order Value</p>
            <p class="text-4xl font-bold">{{ number_format($data['average_order_value'], 3) }} KWD</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Pending Orders</p>
            <p class="text-4xl font-bold">{{ $data['pending_orders'] }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card">
        <div class="card-header">
            <h4 class="text-xl font-bold flex items-center" style="color: #1a1a1a;">
                <i class="fas fa-list mr-2" style="color: #d4af37;"></i>
                Orders by Status
            </h4>
        </div>
        <div class="card-body">
            <div class="space-y-3">
                @foreach($data['by_status'] as $status)
                <div class="flex justify-between items-center p-4 rounded-lg border" style="border-color: #e5e5e5; background: #fafafa;">
                    <div>
                        <span class="font-semibold" style="color: #1a1a1a;">{{ ucfirst($status->status) }}</span>
                        <p class="text-sm" style="color: #666;">{{ $status->count }} orders</p>
                    </div>
                    <span class="text-2xl font-bold" style="color: #10b981;">{{ number_format($status->total, 0) }} KWD</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="text-xl font-bold flex items-center" style="color: #1a1a1a;">
                <i class="fas fa-chart-pie mr-2" style="color: #d4af37;"></i>
                Orders Statistics
            </h4>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-lg p-4 text-center" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="fas fa-check-circle text-3xl text-white mb-2"></i>
                    <p class="text-2xl font-bold text-white">{{ $data['completed_orders'] }}</p>
                    <p class="text-sm text-white opacity-90">Completed</p>
                </div>
                <div class="rounded-lg p-4 text-center" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i class="fas fa-hourglass-half text-3xl text-white mb-2"></i>
                    <p class="text-2xl font-bold text-white">{{ $data['processing_orders'] }}</p>
                    <p class="text-sm text-white opacity-90">Processing</p>
                </div>
                <div class="rounded-lg p-4 text-center" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                    <i class="fas fa-clock text-3xl text-white mb-2"></i>
                    <p class="text-2xl font-bold text-white">{{ $data['pending_orders'] }}</p>
                    <p class="text-sm text-white opacity-90">Pending</p>
                </div>
                <div class="rounded-lg p-4 text-center" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    <i class="fas fa-times-circle text-3xl text-white mb-2"></i>
                    <p class="text-2xl font-bold text-white">{{ $data['cancelled_orders'] }}</p>
                    <p class="text-sm text-white opacity-90">Cancelled</p>
                </div>
            </div>
        </div>
    </div>
</div>
