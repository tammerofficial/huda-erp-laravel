@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #f8f8f8 0%, #ffffff 100%); padding: 2rem;">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="font-bold mb-2" style="font-size: 3rem; color: #1a1a1a;">
                📊 Advanced Accounting Dashboard
            </h1>
            <p style="color: #666; font-size: 1.1rem;">Comprehensive Analytics & Financial Intelligence System</p>
        </div>

        <!-- Filters -->
        <div class="smart-form-card mb-8">
            <form method="GET" class="p-6">
                <div class="form-grid-2">
                    <div class="form-group-enhanced">
                        <label>Date From</label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control">
                    </div>
                    <div class="form-group-enhanced">
                        <label>Date To</label>
                        <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control">
                    </div>
                    <div class="form-group-enhanced">
                        <label>Period</label>
                        <select name="period" class="form-control">
                            <option value="day" {{ $period == 'day' ? 'selected' : '' }}>Daily</option>
                            <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Weekly</option>
                            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Monthly</option>
                            <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Yearly</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="btn-primary w-full">
                            <i class="fas fa-search"></i> Analyze
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Financial Summary - Hero Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Revenue Card -->
            <div class="card" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); border: none; box-shadow: 0 8px 24px rgba(0,0,0,0.15);">
                <div class="card-body text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div style="background: rgba(212, 175, 55, 0.2); padding: 1rem; border-radius: 12px;">
                            <i class="fas fa-dollar-sign text-3xl" style="color: #d4af37;"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-sm" style="color: #d4af37;">Total Revenue</p>
                            <p class="text-3xl font-bold">{{ number_format($financialSummary['total_revenue'], 0) }}</p>
                            <p class="text-xs" style="color: #d4af37;">KWD</p>
                        </div>
                    </div>
                    <div class="text-sm space-y-1" style="background: rgba(255,255,255,0.05); border-radius: 8px; padding: 0.75rem;">
                        <div class="flex justify-between">
                            <span>ERP:</span>
                            <span class="font-semibold">{{ number_format($financialSummary['erp_revenue'], 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>WooCommerce:</span>
                            <span class="font-semibold">{{ number_format($financialSummary['woocommerce_revenue'], 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Costs Card -->
            <div class="card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; box-shadow: 0 8px 24px rgba(239, 68, 68, 0.3);">
                <div class="card-body text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div style="background: rgba(255,255,255,0.2); padding: 1rem; border-radius: 12px;">
                            <i class="fas fa-coins text-3xl"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-90">Total Costs</p>
                            <p class="text-3xl font-bold">{{ number_format($financialSummary['total_costs'], 0) }}</p>
                            <p class="text-xs opacity-90">KWD</p>
                        </div>
                    </div>
                    <div class="text-sm space-y-1" style="background: rgba(255,255,255,0.1); border-radius: 8px; padding: 0.75rem;">
                        <div class="flex justify-between">
                            <span>Production:</span>
                            <span class="font-semibold">{{ number_format($financialSummary['production_costs'], 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Purchases:</span>
                            <span class="font-semibold">{{ number_format($financialSummary['purchase_costs'], 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Payroll:</span>
                            <span class="font-semibold">{{ number_format($financialSummary['payroll_costs'], 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Net Profit Card -->
            <div class="card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);">
                <div class="card-body text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div style="background: rgba(255,255,255,0.2); padding: 1rem; border-radius: 12px;">
                            <i class="fas fa-chart-line text-3xl"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-90">Net Profit</p>
                            <p class="text-3xl font-bold">{{ number_format($financialSummary['net_profit'], 0) }}</p>
                            <p class="text-xs opacity-90">KWD</p>
                        </div>
                    </div>
                    <div class="text-center" style="background: rgba(255,255,255,0.1); border-radius: 8px; padding: 0.75rem;">
                        <p class="text-sm">Profit Margin</p>
                        <p class="text-2xl font-bold">{{ number_format($financialSummary['profit_margin'], 1) }}%</p>
                    </div>
                </div>
            </div>

            <!-- Total Orders Card -->
            <div class="card" style="background: linear-gradient(135deg, #d4af37 0%, #c9a84a 100%); border: none; box-shadow: 0 8px 24px rgba(212, 175, 55, 0.3);">
                <div class="card-body" style="color: #1a1a1a;">
                    <div class="flex items-center justify-between mb-4">
                        <div style="background: rgba(26,26,26,0.1); padding: 1rem; border-radius: 12px;">
                            <i class="fas fa-receipt text-3xl"></i>
                        </div>
                        <div class="text-right">
                            <p class="text-sm opacity-80">Total Orders</p>
                            <p class="text-3xl font-bold">{{ $ordersData['total_orders'] + ($woocommerceData['total_orders'] ?? 0) }}</p>
                            <p class="text-xs opacity-80">Orders</p>
                        </div>
                    </div>
                    <div class="text-sm space-y-1" style="background: rgba(26,26,26,0.05); border-radius: 8px; padding: 0.75rem;">
                        <div class="flex justify-between">
                            <span>ERP:</span>
                            <span class="font-semibold">{{ $ordersData['total_orders'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>WooCommerce:</span>
                            <span class="font-semibold">{{ $woocommerceData['total_orders'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Revenue Timeline -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-xl font-bold flex items-center" style="color: #1a1a1a;">
                        <i class="fas fa-chart-area mr-2" style="color: #10b981;"></i>
                        Revenue Timeline
                    </h3>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Orders Chart -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-xl font-bold flex items-center" style="color: #1a1a1a;">
                        <i class="fas fa-shopping-cart mr-2" style="color: #3b82f6;"></i>
                        Orders by Status
                    </h3>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 300px;">
                        <canvas id="ordersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-2">
                <button onclick="showTab('orders')" class="tab-btn active" data-tab="orders">
                    <i class="fas fa-shopping-cart mr-2"></i>Orders
                </button>
                <button onclick="showTab('customers')" class="tab-btn" data-tab="customers">
                    <i class="fas fa-users mr-2"></i>Customers
                </button>
                <button onclick="showTab('products')" class="tab-btn" data-tab="products">
                    <i class="fas fa-box mr-2"></i>Products
                </button>
                <button onclick="showTab('invoices')" class="tab-btn" data-tab="invoices">
                    <i class="fas fa-file-invoice mr-2"></i>Invoices
                </button>
                <button onclick="showTab('production')" class="tab-btn" data-tab="production">
                    <i class="fas fa-industry mr-2"></i>Production
                </button>
                <button onclick="showTab('materials')" class="tab-btn" data-tab="materials">
                    <i class="fas fa-boxes mr-2"></i>Materials
                </button>
                <button onclick="showTab('warehouses')" class="tab-btn" data-tab="warehouses">
                    <i class="fas fa-warehouse mr-2"></i>Warehouses
                </button>
                <button onclick="showTab('purchasing')" class="tab-btn" data-tab="purchasing">
                    <i class="fas fa-shopping-bag mr-2"></i>Purchasing
                </button>
                <button onclick="showTab('hr')" class="tab-btn" data-tab="hr">
                    <i class="fas fa-user-tie mr-2"></i>HR
                </button>
                <button onclick="showTab('payments')" class="tab-btn" data-tab="payments">
                    <i class="fas fa-credit-card mr-2"></i>Payments
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        
        <!-- Orders Tab -->
        <div id="orders-tab" class="tab-content">
            @include('accounting.partials.orders-analytics', ['data' => $ordersData])
        </div>

        <!-- Customers Tab -->
        <div id="customers-tab" class="tab-content hidden">
            @include('accounting.partials.customers-analytics', ['data' => $customersData])
        </div>

        <!-- Products Tab -->
        <div id="products-tab" class="tab-content hidden">
            @include('accounting.partials.products-analytics', ['data' => $productsData])
        </div>

        <!-- Invoices Tab -->
        <div id="invoices-tab" class="tab-content hidden">
            @include('accounting.partials.invoices-analytics', ['data' => $invoicesData])
        </div>

        <!-- Production Tab -->
        <div id="production-tab" class="tab-content hidden">
            @include('accounting.partials.production-analytics', ['data' => $productionData, 'bomData' => $bomData])
        </div>

        <!-- Materials Tab -->
        <div id="materials-tab" class="tab-content hidden">
            @include('accounting.partials.materials-analytics', ['data' => $materialsData])
        </div>

        <!-- Warehouses Tab -->
        <div id="warehouses-tab" class="tab-content hidden">
            @include('accounting.partials.warehouses-analytics', ['data' => $warehousesData])
        </div>

        <!-- Purchasing Tab -->
        <div id="purchasing-tab" class="tab-content hidden">
            @include('accounting.partials.purchasing-analytics', ['data' => $purchasingData])
        </div>

        <!-- HR Tab -->
        <div id="hr-tab" class="tab-content hidden">
            @include('accounting.partials.hr-analytics', ['data' => $hrData])
        </div>

        <!-- Payments Tab -->
        <div id="payments-tab" class="tab-content hidden">
            @include('accounting.partials.payments-analytics', ['data' => $paymentData, 'wooData' => $woocommerceData])
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: @json($ordersData['timeline']->pluck('period')),
        datasets: [{
            label: 'Revenue (KWD)',
            data: @json($ordersData['timeline']->pluck('total')),
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            fill: true,
            tension: 0.4,
            borderWidth: 3,
            pointBackgroundColor: '#d4af37',
            pointBorderColor: '#1a1a1a',
            pointBorderWidth: 2,
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { 
                display: true, 
                labels: { 
                    color: '#1a1a1a',
                    font: { size: 14, weight: 'bold' }
                } 
            },
            tooltip: {
                backgroundColor: '#1a1a1a',
                titleColor: '#d4af37',
                bodyColor: '#ffffff',
                borderColor: '#d4af37',
                borderWidth: 2,
                padding: 12,
                displayColors: false
            }
        },
        scales: {
            y: { 
                beginAtZero: true,
                ticks: { color: '#666', font: { weight: 'bold' } },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            x: { 
                ticks: { color: '#666', font: { weight: 'bold' } },
                grid: { color: 'rgba(0,0,0,0.05)' }
            }
        }
    }
});

// Orders Chart
const ordersCtx = document.getElementById('ordersChart').getContext('2d');
new Chart(ordersCtx, {
    type: 'bar',
    data: {
        labels: @json($ordersData['by_status']->pluck('status')),
        datasets: [{
            label: 'Orders Count',
            data: @json($ordersData['by_status']->pluck('count')),
            backgroundColor: [
                'rgba(59, 130, 246, 0.9)',
                'rgba(251, 191, 36, 0.9)',
                'rgba(16, 185, 129, 0.9)',
                'rgba(239, 68, 68, 0.9)'
            ],
            borderColor: [
                '#3b82f6',
                '#fbbf24',
                '#10b981',
                '#ef4444'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#1a1a1a',
                titleColor: '#d4af37',
                bodyColor: '#ffffff',
                borderColor: '#d4af37',
                borderWidth: 2,
                padding: 12,
                displayColors: false
            }
        },
        scales: {
            y: { 
                beginAtZero: true,
                ticks: { color: '#666', font: { weight: 'bold' } },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            x: { 
                ticks: { color: '#666', font: { weight: 'bold' } },
                grid: { display: false }
            }
        }
    }
});

// Tab Switching
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to clicked button
    document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
}
</script>

<style>
.tab-btn {
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    background: #ffffff;
    color: #1a1a1a;
    border: 2px solid #e5e5e5;
    transition: all 0.3s ease;
    font-weight: 600;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    cursor: pointer;
}

.tab-btn:hover {
    background: #f8f8f8;
    border-color: #d4af37;
    color: #1a1a1a;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
}

.tab-btn.active {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    border-color: #d4af37;
    color: #d4af37;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}

.tab-content {
    animation: fadeIn 0.4s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection
