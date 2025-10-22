@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

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
        font-size: 2rem;
        font-weight: 700;
        margin: 5px 0;
        letter-spacing: -1px;
    }
    .stat-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.9;
        font-weight: 500;
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
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
    .welcome-banner {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        color: #fff;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 20px;
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
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .welcome-banner p {
        opacity: 0.9;
        font-size: 1rem;
    }
    .action-btn {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .action-btn:hover {
        border-color: #d4af37;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(212,175,55,0.2);
    }
    .action-btn i {
        font-size: 2rem;
        margin-bottom: 10px;
        color: #d4af37;
    }
</style>

<div class="px-6 py-6" style="max-width: 100%; margin: 0 auto;">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1>Welcome back, {{ auth()->user()->name ?? 'User' }}! 👋</h1>
                <p class="mb-0">Here's what's happening with your business today.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="text-white-50">{{ now()->format('l, F j, Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-3">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Total Orders</div>
                        <div class="stat-number">{{ $stats['total_orders'] ?? 0 }}</div>
                        <small class="text-white-50" style="font-size: 0.75rem;">
                            <i class="fas fa-arrow-up"></i> All time
                        </small>
                    </div>
                    <div class="stat-icon">
                        📦
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="stat-card gold-accent">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Total Revenue</div>
                        <div class="stat-number">{{ number_format($stats['total_revenue'] ?? 0, 0) }}</div>
                        <small style="opacity: 0.7; font-size: 0.75rem;">
                            <i class="fas fa-arrow-up"></i> KWD
                        </small>
                    </div>
                    <div class="stat-icon">
                        💰
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Pending Orders</div>
                        <div class="stat-number">{{ $stats['pending_orders'] ?? 0 }}</div>
                        <small class="text-white-50" style="font-size: 0.75rem;">
                            <i class="fas fa-clock"></i> Attention
                        </small>
                    </div>
                    <div class="stat-icon">
                        ⏱️
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="stat-label">Customers</div>
                        <div class="stat-number">{{ $stats['total_customers'] ?? 0 }}</div>
                        <small class="text-white-50" style="font-size: 0.75rem;">
                            <i class="fas fa-users"></i> Active
                        </small>
                    </div>
                    <div class="stat-icon">
                        👥
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="luxury-card">
                <div class="p-4 border-bottom">
                    <h3 class="section-title mb-0">Recent Orders</h3>
                </div>
                <div class="p-4">
                    @if(isset($recent_orders) && $recent_orders->count() > 0)
                        <div class="table-responsive">
                            <table class="luxury-table table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_orders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->id }}</strong></td>
                                        <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                        <td><strong>{{ number_format($order->total_amount ?? 0, 2) }} KWD</strong></td>
                                        <td>
                                            <span class="badge-luxury {{ $order->status == 'completed' ? 'success' : 'warning' }}">
                                                {{ $order->status ?? 'pending' }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            No recent orders
                        </div>
                    @endif
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
                            <div class="text-muted small">Completed Orders</div>
                            <strong>Orders Completed</strong>
                        </div>
                        <div class="quick-stat-badge">{{ $stats['completed_orders'] ?? 0 }}</div>
                    </div>
                    <div class="quick-stat-item">
                        <div>
                            <div class="text-muted small">Total Products</div>
                            <strong>Products Available</strong>
                        </div>
                        <div class="quick-stat-badge">{{ $stats['total_products'] ?? 0 }}</div>
                    </div>
                    <div class="quick-stat-item">
                        <div>
                            <div class="text-muted small">In Production</div>
                            <strong>Active Production</strong>
                        </div>
                        <div class="quick-stat-badge">{{ $stats['in_production'] ?? 0 }}</div>
                    </div>
                    <div class="quick-stat-item" style="border-color: #dc3545;">
                        <div>
                            <div class="text-danger small">⚠️ Alert</div>
                            <strong>Low Stock Materials</strong>
                        </div>
                        <div class="quick-stat-badge" style="background: #dc3545; color: #fff;">{{ $stats['low_stock_materials'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="luxury-card mt-4">
        <div class="p-4 border-bottom">
            <h3 class="section-title mb-0">Quick Actions</h3>
        </div>
        <div class="p-4">
            <div class="row g-3">
                <div class="col-md-2 col-6">
                    <a href="{{ route('orders.create') }}" class="action-btn text-decoration-none d-block">
                        <i class="fas fa-plus-circle"></i>
                        <div class="small font-weight-bold text-dark">New Order</div>
                    </a>
                </div>
                <div class="col-md-2 col-6">
                    <a href="{{ route('customers.create') }}" class="action-btn text-decoration-none d-block">
                        <i class="fas fa-user-plus"></i>
                        <div class="small font-weight-bold text-dark">Add Customer</div>
                    </a>
                </div>
                <div class="col-md-2 col-6">
                    <a href="{{ route('products.create') }}" class="action-btn text-decoration-none d-block">
                        <i class="fas fa-box"></i>
                        <div class="small font-weight-bold text-dark">Add Product</div>
                    </a>
                </div>
                <div class="col-md-2 col-6">
                    <a href="{{ route('materials.create') }}" class="action-btn text-decoration-none d-block">
                        <i class="fas fa-cube"></i>
                        <div class="small font-weight-bold text-dark">Add Material</div>
                    </a>
                </div>
                <div class="col-md-2 col-6">
                    <a href="{{ route('suppliers.create') }}" class="action-btn text-decoration-none d-block">
                        <i class="fas fa-industry"></i>
                        <div class="small font-weight-bold text-dark">Add Supplier</div>
                    </a>
                </div>
                <div class="col-md-2 col-6">
                    <a href="{{ route('employees.create') }}" class="action-btn text-decoration-none d-block">
                        <i class="fas fa-user-tie"></i>
                        <div class="small font-weight-bold text-dark">Add Employee</div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(isset($production_orders) && $production_orders->count() > 0)
    <!-- Production Orders -->
    <div class="luxury-card mt-4">
        <div class="p-4 border-bottom">
            <h3 class="section-title mb-0">Active Production</h3>
        </div>
        <div class="p-4">
            @foreach($production_orders as $production)
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong class="d-block">{{ $production->product->name ?? 'N/A' }}</strong>
                        <small class="text-muted">Order #{{ $production->order_id ?? 'N/A' }}</small>
                    </div>
                    <span class="badge-luxury success">In Progress</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
