@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    .luxury-card {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .luxury-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    .stat-card {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        border: none;
        border-radius: 16px;
        padding: 30px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: linear-gradient(135deg, rgba(212,175,55,0.1) 0%, transparent 100%);
        border-radius: 50%;
        transform: translate(50%, -50%);
    }
    .stat-card.gold-accent {
        background: linear-gradient(135deg, #d4af37 0%, #f2d06b 100%);
        color: #000;
    }
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 10px 0;
        letter-spacing: -1px;
    }
    .stat-label {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.9;
        font-weight: 500;
    }
    .stat-icon {
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }
    .gold-accent .stat-icon {
        background: rgba(0,0,0,0.1);
    }
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 3px solid #d4af37;
        display: inline-block;
    }
    .luxury-table {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
    }
    .luxury-table thead {
        background: #1a1a1a;
        color: #fff;
    }
    .luxury-table thead th {
        border: none;
        padding: 15px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .luxury-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.2s ease;
    }
    .luxury-table tbody tr:hover {
        background: #fafafa;
    }
    .luxury-table tbody td {
        padding: 15px;
        vertical-align: middle;
    }
    .badge-luxury {
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .badge-luxury.success {
        background: #1a1a1a;
        color: #d4af37;
    }
    .badge-luxury.warning {
        background: #d4af37;
        color: #1a1a1a;
    }
    .badge-luxury.danger {
        background: #dc3545;
        color: #fff;
    }
    .badge-luxury.info {
        background: #e5e5e5;
        color: #1a1a1a;
    }
    .progress-luxury {
        height: 8px;
        background: #f0f0f0;
        border-radius: 10px;
        overflow: hidden;
    }
    .progress-luxury .progress-bar {
        background: linear-gradient(90deg, #1a1a1a 0%, #d4af37 100%);
        border-radius: 10px;
    }
    .quick-stat-item {
        padding: 15px 20px;
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 10px;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s ease;
    }
    .quick-stat-item:hover {
        border-color: #d4af37;
        transform: translateX(5px);
    }
    .quick-stat-badge {
        background: #1a1a1a;
        color: #d4af37;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 1rem;
    }
    .alert-item {
        padding: 12px;
        background: #fff;
        border-left: 4px solid #dc3545;
        border-radius: 8px;
        margin-bottom: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .welcome-banner {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        color: #fff;
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(212,175,55,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    .welcome-banner h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .welcome-banner p {
        opacity: 0.9;
        font-size: 1.1rem;
    }
</style>

<div class="container-fluid px-4 py-4">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1>Welcome back, {{ auth()->user()->name }}! 👋</h1>
                <p class="mb-0">Here's what's happening with your business today.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="text-white-50">{{ now()->format('l, F j, Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Total Orders</div>
                        <div class="stat-number">{{ $totalOrders }}</div>
                        <small class="text-white-50">
                            <i class="fas fa-arrow-up"></i> 12% from last month
                        </small>
                    </div>
                    <div class="stat-icon">
                        📦
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card gold-accent">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Total Revenue</div>
                        <div class="stat-number">{{ number_format($totalRevenue, 0) }}</div>
                        <small style="opacity: 0.7;">
                            <i class="fas fa-arrow-up"></i> KWD this month
                        </small>
                    </div>
                    <div class="stat-icon">
                        💰
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Pending Orders</div>
                        <div class="stat-number">{{ $pendingOrders }}</div>
                        <small class="text-white-50">
                            <i class="fas fa-clock"></i> Requires attention
                        </small>
                    </div>
                    <div class="stat-icon">
                        ⏱️
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Active Users</div>
                        <div class="stat-number">{{ $activeUsers }}</div>
                        <small class="text-white-50">
                            <i class="fas fa-users"></i> Team members
                        </small>
                    </div>
                    <div class="stat-icon">
                        👥
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="luxury-card">
                <div class="p-4 border-bottom">
                    <h3 class="section-title mb-0">Recent Orders</h3>
                </div>
                <div class="p-4">
                    <div class="table-responsive">
                        <table class="luxury-table table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->customer->name }}</td>
                                    <td><strong>{{ number_format($order->final_amount, 2) }} KWD</strong></td>
                                    <td>
                                        <span class="badge-luxury {{ $order->status == 'completed' ? 'success' : 'warning' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td>{{ $order->order_date->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-dark">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                            No recent orders
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-4">
            <div class="luxury-card">
                <div class="p-4 border-bottom">
                    <h3 class="section-title mb-0">Quick Stats</h3>
                </div>
                <div class="p-4">
                    <div class="quick-stat-item">
                        <div>
                            <div class="text-muted small">Total Products</div>
                            <strong>Products Available</strong>
                        </div>
                        <div class="quick-stat-badge">{{ $totalProducts }}</div>
                    </div>
                    <div class="quick-stat-item">
                        <div>
                            <div class="text-muted small">Total Materials</div>
                            <strong>Materials in Stock</strong>
                        </div>
                        <div class="quick-stat-badge">{{ $totalMaterials }}</div>
                    </div>
                    <div class="quick-stat-item">
                        <div>
                            <div class="text-muted small">Production Orders</div>
                            <strong>Active Production</strong>
                        </div>
                        <div class="quick-stat-badge">{{ $productionOrders }}</div>
                    </div>
                    <div class="quick-stat-item" style="border-color: #dc3545;">
                        <div>
                            <div class="text-danger small">⚠️ Alert</div>
                            <strong>Low Stock Items</strong>
                        </div>
                        <div class="quick-stat-badge" style="background: #dc3545; color: #fff;">{{ $lowStockItems }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <!-- Production Status -->
        <div class="col-lg-6">
            <div class="luxury-card">
                <div class="p-4 border-bottom">
                    <h3 class="section-title mb-0">Production Status</h3>
                </div>
                <div class="p-4">
                    @forelse($recentProductionOrders as $productionOrder)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong class="d-block">{{ $productionOrder->production_number }}</strong>
                                <small class="text-muted">{{ $productionOrder->product->name }}</small>
                            </div>
                            <span class="badge-luxury {{ $productionOrder->status == 'completed' ? 'success' : 'warning' }}">
                                {{ $productionOrder->status }}
                            </span>
                        </div>
                        @php
                            $completedStages = $productionOrder->stages->where('status', 'completed')->count();
                            $totalStages = $productionOrder->stages->count();
                            $progress = $totalStages > 0 ? ($completedStages / $totalStages) * 100 : 0;
                        @endphp
                        <div class="progress-luxury">
                            <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%">
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block">{{ number_format($progress, 0) }}% Complete</small>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-industry fa-3x mb-3 d-block"></i>
                        No active production orders
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Inventory Alerts -->
        <div class="col-lg-6">
            <div class="luxury-card">
                <div class="p-4 border-bottom">
                    <h3 class="section-title mb-0">Inventory Alerts</h3>
                </div>
                <div class="p-4">
                    @if($lowStockMaterials->count() > 0)
                        @foreach($lowStockMaterials->take(5) as $material)
                        <div class="alert-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <strong class="d-block">{{ $material->name }}</strong>
                                    <small class="text-muted">
                                        Stock: {{ $material->inventory ? $material->inventory->quantity : 0 }} / 
                                        Reorder: {{ $material->reorder_level }}
                                    </small>
                                </div>
                                @if(($material->inventory ? $material->inventory->quantity : 0) == 0)
                                    <span class="badge-luxury danger">Out of Stock</span>
                                @else
                                    <span class="badge-luxury warning">Low Stock</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        <div class="text-center mt-3">
                            <a href="{{ route('inventory.low-stock') }}" class="btn btn-outline-dark btn-sm">
                                View All Alerts <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-check-circle fa-3x mb-3 d-block" style="color: #d4af37;"></i>
                            <strong>All inventory levels are healthy</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
