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
        <div class="flex items-center justify-between">
            <div>
                <h1>Welcome back, {{ auth()->user()->name ?? 'User' }}!</h1>
                <p class="mb-0">Here's what's happening with your business today.</p>
            </div>
            <div class="text-right">
                <div class="text-white" style="font-size: 0.95rem;">{{ now()->format('l, F j, Y') }}</div>
                <div class="text-white-50" style="font-size: 0.85rem; opacity: 0.8;" dir="rtl">
                    @php
                        $date = now();
                        // Simple Hijri conversion approximation
                        $gregorianYear = $date->year;
                        $gregorianMonth = $date->month;
                        $gregorianDay = $date->day;
                        
                        // Calculate Julian Day Number
                        $a = floor((14 - $gregorianMonth) / 12);
                        $y = $gregorianYear + 4800 - $a;
                        $m = $gregorianMonth + (12 * $a) - 3;
                        $jd = $gregorianDay + floor((153 * $m + 2) / 5) + (365 * $y) + floor($y / 4) - floor($y / 100) + floor($y / 400) - 32045;
                        
                        // Convert to Hijri
                        $l = $jd - 1948440 + 10632;
                        $n = floor(($l - 1) / 10631);
                        $l = $l - 10631 * $n + 354;
                        $j = (floor((10985 - $l) / 5316)) * (floor((50 * $l) / 17719)) + (floor($l / 5670)) * (floor((43 * $l) / 15238));
                        $l = $l - (floor((30 - $j) / 15)) * (floor((17719 * $j) / 50)) - (floor($j / 16)) * (floor((15238 * $j) / 43)) + 29;
                        $hijriMonth = floor((24 * $l) / 709);
                        $hijriDay = $l - floor((709 * $hijriMonth) / 24);
                        $hijriYear = 30 * $n + $j - 30;
                        
                        $hijriMonths = [
                            1 => 'محرم', 2 => 'صفر', 3 => 'ربيع الأول', 4 => 'ربيع الثاني',
                            5 => 'جمادى الأولى', 6 => 'جمادى الثانية', 7 => 'رجب', 8 => 'شعبان',
                            9 => 'رمضان', 10 => 'شوال', 11 => 'ذو القعدة', 12 => 'ذو الحجة'
                        ];
                        
                        $hijriMonthName = $hijriMonths[$hijriMonth];
                    @endphp
                    {{ $hijriDay }} {{ $hijriMonthName }} {{ $hijriYear }} هـ
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
        <!-- In Production -->
        <div>
            <div class="stat-card">
                <div class="flex justify-between items-start">
                    <div class="flex-grow">
                        <div class="stat-label">In Production</div>
                        <div class="stat-number">{{ $stats['in_production'] ?? 0 }}</div>
                        <small class="text-white-50" style="font-size: 0.75rem;">
                            <i class="fas fa-cogs"></i> Active Orders
                        </small>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-industry"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Today -->
        <div>
            <div class="stat-card gold-accent">
                <div class="flex justify-between items-start">
                    <div class="flex-grow">
                        <div class="stat-label">Completed Today</div>
                        <div class="stat-number">{{ $stats['completed_today'] ?? 0 }}</div>
                        <small style="opacity: 0.7; font-size: 0.75rem;">
                            <i class="fas fa-check-circle"></i> Production Orders
                        </small>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Employees -->
        <div>
            <div class="stat-card">
                <div class="flex justify-between items-start">
                    <div class="flex-grow">
                        <div class="stat-label">Active Staff</div>
                        <div class="stat-number">{{ $stats['active_employees'] ?? 0 }}</div>
                        <small class="text-white-50" style="font-size: 0.75rem;">
                            <i class="fas fa-user-check"></i> Working Now
                        </small>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div>
            <div class="stat-card">
                <div class="flex justify-between items-start">
                    <div class="flex-grow">
                        <div class="stat-label">Pending Orders</div>
                        <div class="stat-number">{{ $stats['pending_orders'] ?? 0 }}</div>
                        <small class="text-white-50" style="font-size: 0.75rem;">
                            <i class="fas fa-clock"></i> Needs Attention
                        </small>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        <!-- Recent Orders -->
        <div class="lg:col-span-2">
            <div class="luxury-card">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="section-title mb-0">Recent Orders</h3>
                </div>
                <div class="p-4">
                    @if(isset($recent_orders) && $recent_orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="luxury-table w-full mb-0">
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
                        <div class="text-center py-5 text-gray-500">
                            <i class="fas fa-inbox text-5xl mb-3 block"></i>
                            No recent orders
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="lg:col-span-1">
            <div class="luxury-card">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="section-title mb-0">Production Stats</h3>
                </div>
                <div class="p-4">
                    <div class="quick-stat-item">
                        <div>
                            <div class="text-muted small"><i class="fas fa-bullseye mr-1"></i> Today's Target</div>
                            <strong>Stages Completed</strong>
                        </div>
                        <div class="quick-stat-badge">{{ $stats['stages_completed_today'] ?? 0 }}</div>
                    </div>
                    <div class="quick-stat-item">
                        <div>
                            <div class="text-muted small"><i class="fas fa-box mr-1"></i> This Week</div>
                            <strong>Orders Completed</strong>
                        </div>
                        <div class="quick-stat-badge">{{ $stats['completed_this_week'] ?? 0 }}</div>
                    </div>
                    <div class="quick-stat-item">
                        <div>
                            <div class="text-muted small"><i class="fas fa-cubes mr-1"></i> Total Materials</div>
                            <strong>Materials in Stock</strong>
                        </div>
                        <div class="quick-stat-badge">{{ $stats['total_materials'] ?? 0 }}</div>
                    </div>
                    <div class="quick-stat-item" style="border-color: #d4af37;">
                        <div>
                            <div style="color: #d4af37; font-size: 0.75rem;"><i class="fas fa-exclamation-triangle mr-1"></i> Alert</div>
                            <strong>Low Stock Materials</strong>
                        </div>
                        <div class="quick-stat-badge" style="background: linear-gradient(135deg, #d4af37 0%, #f2d06b 100%); color: #1a1a1a;">{{ $stats['low_stock_materials'] ?? 0 }}</div>
                    </div>
                    <div class="quick-stat-item">
                        <div>
                            <div class="text-muted small"><i class="fas fa-user-friends mr-1"></i> Team</div>
                            <strong>Total Employees</strong>
                        </div>
                        <div class="quick-stat-badge">{{ $stats['total_employees'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="luxury-card mt-4">
        <div class="p-4 border-b border-gray-200">
            <h3 class="section-title mb-0">Quick Actions</h3>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                <a href="{{ route('orders.create') }}" class="action-btn no-underline block">
                    <i class="fas fa-plus-circle"></i>
                    <div class="text-sm font-semibold text-gray-800">New Order</div>
                </a>
                <a href="{{ route('customers.create') }}" class="action-btn no-underline block">
                    <i class="fas fa-user-plus"></i>
                    <div class="text-sm font-semibold text-gray-800">Add Customer</div>
                </a>
                <a href="{{ route('products.create') }}" class="action-btn no-underline block">
                    <i class="fas fa-box"></i>
                    <div class="text-sm font-semibold text-gray-800">Add Product</div>
                </a>
                <a href="{{ route('materials.create') }}" class="action-btn no-underline block">
                    <i class="fas fa-cube"></i>
                    <div class="text-sm font-semibold text-gray-800">Add Material</div>
                </a>
                <a href="{{ route('suppliers.create') }}" class="action-btn no-underline block">
                    <i class="fas fa-industry"></i>
                    <div class="text-sm font-semibold text-gray-800">Add Supplier</div>
                </a>
                <a href="{{ route('employees.create') }}" class="action-btn no-underline block">
                    <i class="fas fa-user-tie"></i>
                    <div class="text-sm font-semibold text-gray-800">Add Employee</div>
                </a>
            </div>
        </div>
    </div>

    @if(isset($production_orders) && $production_orders->count() > 0)
    <!-- Active Production Orders -->
    <div class="luxury-card mt-4">
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="section-title mb-0"><i class="fas fa-industry mr-2"></i> Active Production Orders</h3>
                <a href="{{ route('productions.dashboard') }}" class="text-sm hover:underline font-semibold" style="color: #d4af37;">
                    View Workshop Dashboard <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($production_orders as $production)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-gray-900 hover:shadow-md transition-all">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 text-sm mb-1">{{ $production->production_number }}</h4>
                            <p class="text-xs text-gray-600">{{ $production->product->name ?? 'N/A' }}</p>
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            @if($production->status == 'in-progress') text-white" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%)
                            @elseif($production->status == 'pending') text-gray-900" style="background: linear-gradient(135deg, #d4af37 0%, #f2d06b 100%)
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst(str_replace('-', ' ', $production->status)) }}
                        </span>
                    </div>
                    
                    <div class="space-y-2 mb-3">
                        <div class="flex items-center text-xs text-gray-600">
                            <i class="fas fa-user w-4"></i>
                            <span class="ml-2">{{ $production->order->customer->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center text-xs text-gray-600">
                            <i class="fas fa-box w-4"></i>
                            <span class="ml-2">Qty: {{ $production->quantity }}</span>
                        </div>
                        <div class="flex items-center text-xs text-gray-600">
                            <i class="fas fa-calendar w-4"></i>
                            <span class="ml-2">Due: {{ $production->due_date ? $production->due_date->format('M d, Y') : 'Not set' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <span class="text-xs px-2 py-1 rounded-full
                            @if($production->priority == 'urgent') text-white" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%)
                            @elseif($production->priority == 'high') text-gray-900" style="background: linear-gradient(135deg, #d4af37 0%, #f2d06b 100%)
                            @else bg-gray-100 text-gray-800
                            @endif">
                            <i class="fas fa-flag mr-1"></i>
                            {{ ucfirst($production->priority ?? 'normal') }}
                        </span>
                        <a href="{{ route('productions.show', $production) }}" class="text-xs hover:underline font-semibold" style="color: #d4af37;">
                            View Details <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            @if($production_orders->count() >= 5)
            <div class="mt-4 text-center">
                <a href="{{ route('productions.index') }}" class="inline-block px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                    View All Production Orders
                </a>
            </div>
            @endif
        </div>
    </div>
    @else
    <!-- No Active Production -->
    <div class="luxury-card mt-4">
        <div class="p-4 border-b border-gray-200">
            <h3 class="section-title mb-0"><i class="fas fa-industry mr-2"></i> Active Production Orders</h3>
        </div>
        <div class="p-8 text-center">
            <i class="fas fa-industry text-gray-300 text-5xl mb-4"></i>
            <h4 class="text-lg font-semibold text-gray-700 mb-2">No Active Production Orders</h4>
            <p class="text-gray-500 mb-4">Start a new production order to see it here</p>
            <a href="{{ route('productions.create') }}" class="inline-block px-6 py-2 rounded-lg transition-colors btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Create Production Order
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
