@extends('layouts.app')

@section('title', 'Cost Management Dashboard')
@section('page-title', 'Cost Management Dashboard')

@section('content')
<div class="container-fluid">
    <!-- 1. Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    📊 Cost Management Dashboard
                </h2>
                <p class="text-gray-600 mt-1">Monitor and analyze product costs, order profitability, and cost trends</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="bulkRecalculate()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-sync mr-2"></i>
                    Bulk Recalculate
                </button>
                <a href="{{ route('cost-management.products') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-box mr-2"></i>
                    Manage Products
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Date Range Filter -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <form method="GET" action="{{ route('cost-management.dashboard') }}" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                <i class="fas fa-search mr-2"></i>
                Filter
            </button>
        </form>
    </div>

    <!-- 3. Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Products</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_products']) }}</h3>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Products with Costs -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">With Costs</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['products_with_costs']) }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="text-green-600">{{ $stats['total_products'] > 0 ? round(($stats['products_with_costs'] / $stats['total_products']) * 100, 1) : 0 }}%</span> coverage
                    </p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Orders</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_orders']) }}</h3>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-shopping-cart text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Orders with Costs -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Orders with Costs</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['orders_with_costs']) }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="text-green-600">{{ $stats['total_orders'] > 0 ? round(($stats['orders_with_costs'] / $stats['total_orders']) * 100, 1) : 0 }}%</span> coverage
                    </p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <i class="fas fa-chart-line text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Materials -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Materials</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_materials']) }}</h3>
                </div>
                <div class="bg-teal-100 rounded-full p-3">
                    <i class="fas fa-cube text-teal-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active BOMs -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Active BOMs</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['active_boms']) }}</h3>
                </div>
                <div class="bg-indigo-100 rounded-full p-3">
                    <i class="fas fa-clipboard-list text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Cost Analysis Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Cost Analysis -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">💰 Cost Analysis</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-gray-600">Average Product Cost</span>
                    <span class="text-gray-900 font-semibold">${{ number_format($costAnalysis['average_product_cost'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-gray-600">Average Order Cost</span>
                    <span class="text-gray-900 font-semibold">${{ number_format($costAnalysis['average_order_cost'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-gray-600">Total Material Value</span>
                    <span class="text-gray-900 font-semibold">${{ number_format($costAnalysis['total_material_value'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-gray-600">Production Costs</span>
                    <span class="text-gray-900 font-semibold">${{ number_format($costAnalysis['production_costs'], 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Recent Updates -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">🔄 Recent Cost Updates</h3>
            <div class="space-y-3">
                @forelse($recentUpdates as $product)
                <div class="flex items-center justify-between py-3 border-b border-gray-200 last:border-b-0">
                    <div>
                        <p class="text-gray-900 font-medium">{{ $product->name }}</p>
                        <p class="text-gray-500 text-sm">{{ $product->sku }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-green-600 font-semibold">${{ number_format($product->cost, 2) }}</p>
                        <p class="text-gray-500 text-xs">{{ $product->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No recent updates</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 5. Cost Trends Chart -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">📈 Cost Trends (Last 6 Months)</h3>
        <div class="h-64">
            <canvas id="costTrendsChart"></canvas>
        </div>
    </div>

    <!-- 6. Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('cost-management.products') }}" class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-lg p-3 mr-4">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-lg text-gray-900">Product Costs</h4>
                    <p class="text-gray-600 text-sm">Manage product cost calculations</p>
                </div>
            </div>
        </a>

        <a href="{{ route('cost-management.orders') }}" class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-lg p-3 mr-4">
                    <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-lg text-gray-900">Order Costs</h4>
                    <p class="text-gray-600 text-sm">Analyze order profitability</p>
                </div>
            </div>
        </a>

        <a href="{{ route('cost-management.profitability') }}" class="bg-white rounded-lg shadow-sm border p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="bg-purple-100 rounded-lg p-3 mr-4">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-lg text-gray-900">Profitability Analysis</h4>
                    <p class="text-gray-600 text-sm">Detailed profit analysis</p>
                </div>
            </div>
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Cost Trends Chart
const costTrendsData = @json($costTrends);
const ctx = document.getElementById('costTrendsChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: costTrendsData.map(item => item.month),
        datasets: [{
            label: 'Average Cost',
            data: costTrendsData.map(item => item.avg_cost),
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: '#374151'
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: '#6b7280'
                },
                grid: {
                    color: '#e5e7eb'
                }
            },
            y: {
                ticks: {
                    color: '#6b7280'
                },
                grid: {
                    color: '#e5e7eb'
                }
            }
        }
    }
});

// Bulk Recalculate Function
function bulkRecalculate() {
    if (confirm('Are you sure you want to recalculate costs for all products? This may take a few minutes.')) {
        fetch('{{ route("cost-management.products.bulk-recalculate") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Successfully updated ${data.updated} products!`);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while recalculating costs.');
        });
    }
}
</script>
@endsection