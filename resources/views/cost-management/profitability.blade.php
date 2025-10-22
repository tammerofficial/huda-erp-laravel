@extends('layouts.app')

@section('title', 'Profitability Analysis')
@section('page-title', 'Profitability Analysis')

@section('content')
<div class="container-fluid">
    <!-- 1. Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    📈 Profitability Analysis
                </h2>
                <p class="text-gray-600 mt-1">Comprehensive analysis of revenue, costs, and profit margins</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('cost-management.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Cost Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Date Range Filter -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <form method="GET" action="{{ route('cost-management.profitability') }}" class="flex gap-4 items-end">
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

    <!-- 3. Overall Profitability Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
                    <h3 class="text-2xl font-bold text-gray-900">${{ number_format($profitability['total_revenue'], 2) }}</h3>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Costs</p>
                    <h3 class="text-2xl font-bold text-gray-900">${{ number_format($profitability['total_costs'], 2) }}</h3>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Gross Profit</p>
                    <h3 class="text-2xl font-bold {{ $profitability['gross_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        ${{ number_format($profitability['gross_profit'], 2) }}
                    </h3>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Profit Margin</p>
                    <h3 class="text-2xl font-bold {{ $profitability['profit_margin'] >= 20 ? 'text-green-600' : ($profitability['profit_margin'] >= 10 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ number_format($profitability['profit_margin'], 1) }}%
                    </h3>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-percentage text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Monthly Profitability Chart -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">📊 Monthly Profitability</h3>
            <div class="h-64">
                <canvas id="monthlyProfitabilityChart"></canvas>
            </div>
        </div>

        <!-- Cost Breakdown Chart -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">🥧 Cost Breakdown</h3>
            <div class="h-64">
                <canvas id="costBreakdownChart"></canvas>
            </div>
        </div>
    </div>

    <!-- 5. Top Profitable Products -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">🏆 Top Profitable Products</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Sold</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Costs</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Margin</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topProducts as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ substr($product->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($product->total_quantity) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                            ${{ number_format($product->total_revenue, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-orange-600 font-semibold">
                            ${{ number_format($product->total_cost, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $product->profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ${{ number_format($product->profit, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($product->total_revenue > 0)
                                @php
                                    $margin = ($product->profit / $product->total_revenue) * 100;
                                @endphp
                                <span class="text-sm font-semibold {{ $margin >= 30 ? 'text-green-600' : ($margin >= 15 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($margin, 1) }}%
                                </span>
                            @else
                                <span class="text-gray-500">N/A</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-box-open text-4xl text-gray-300 mb-2"></i>
                                <p class="text-lg">No profitable products found</p>
                                <p class="text-sm">Try adjusting your date range</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 6. Cost Breakdown Details -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">💰 Detailed Cost Breakdown</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-medium">Material Costs</p>
                        <p class="text-2xl font-bold text-blue-900">${{ number_format($costBreakdown['material_costs'], 2) }}</p>
                    </div>
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cube text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-medium">Labor Costs</p>
                        <p class="text-2xl font-bold text-green-900">${{ number_format($costBreakdown['labor_costs'], 2) }}</p>
                    </div>
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-purple-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-600 text-sm font-medium">Overhead Costs</p>
                        <p class="text-2xl font-bold text-purple-900">${{ number_format($costBreakdown['overhead_costs'], 2) }}</p>
                    </div>
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-purple-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-orange-50 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-600 text-sm font-medium">Shipping Costs</p>
                        <p class="text-2xl font-bold text-orange-900">${{ number_format($costBreakdown['shipping_costs'], 2) }}</p>
                    </div>
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-truck text-orange-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Profitability Chart
const monthlyData = @json($monthlyProfitability);
const monthlyCtx = document.getElementById('monthlyProfitabilityChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'bar',
    data: {
        labels: monthlyData.map(item => item.month),
        datasets: [
            {
                label: 'Revenue',
                data: monthlyData.map(item => item.revenue),
                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                borderColor: 'rgb(34, 197, 94)',
                borderWidth: 1
            },
            {
                label: 'Costs',
                data: monthlyData.map(item => item.costs),
                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                borderColor: 'rgb(239, 68, 68)',
                borderWidth: 1
            },
            {
                label: 'Profit',
                data: monthlyData.map(item => item.profit),
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
            }
        ]
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

// Cost Breakdown Chart
const costBreakdownData = @json($costBreakdown);
const breakdownCtx = document.getElementById('costBreakdownChart').getContext('2d');
new Chart(breakdownCtx, {
    type: 'doughnut',
    data: {
        labels: ['Material Costs', 'Labor Costs', 'Overhead Costs', 'Shipping Costs'],
        datasets: [{
            data: [
                costBreakdownData.material_costs,
                costBreakdownData.labor_costs,
                costBreakdownData.overhead_costs,
                costBreakdownData.shipping_costs
            ],
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(34, 197, 94, 0.8)',
                'rgba(168, 85, 247, 0.8)',
                'rgba(249, 115, 22, 0.8)'
            ],
            borderColor: [
                'rgb(59, 130, 246)',
                'rgb(34, 197, 94)',
                'rgb(168, 85, 247)',
                'rgb(249, 115, 22)'
            ],
            borderWidth: 2
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
        }
    }
});
</script>
@endsection