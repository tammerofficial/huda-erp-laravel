@extends('layouts.app')

@section('title', 'Production Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Production Reports</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.production') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i> Generate Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">Total Orders</h4>
                                    <h2>{{ $totalOrders }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-industry fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">Completed</h4>
                                    <h2>{{ $completedOrders }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">In Progress</h4>
                                    <h2>{{ $inProgressOrders }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">Efficiency</h4>
                                    <h2>{{ $efficiency }}%</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-chart-line fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Production Orders Table -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Production Orders Report</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Production #</th>
                                    <th>Product</th>
                                    <th>Order #</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productionOrders as $order)
                                <tr>
                                    <td>{{ $order->production_number }}</td>
                                    <td>{{ $order->product->name }}</td>
                                    <td>{{ $order->order->order_number }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>
                                        <span class="badge badge-{{ $order->status == 'completed' ? 'success' : ($order->status == 'in-progress' ? 'warning' : 'secondary') }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $order->priority == 'urgent' ? 'danger' : ($order->priority == 'high' ? 'warning' : 'info') }}">
                                            {{ $order->priority }}
                                        </span>
                                    </td>
                                    <td>{{ $order->start_date ? $order->start_date->format('Y-m-d') : 'Not Started' }}</td>
                                    <td>{{ $order->end_date ? $order->end_date->format('Y-m-d') : 'Not Completed' }}</td>
                                    <td>
                                        @php
                                            $completedStages = $order->productionStages->where('status', 'completed')->count();
                                            $totalStages = $order->productionStages->count();
                                            $progress = $totalStages > 0 ? ($completedStages / $totalStages) * 100 : 0;
                                        @endphp
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%">
                                                {{ number_format($progress, 0) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No production orders found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $productionOrders->links() }}
                    </div>
                </div>
            </div>

            <!-- Production Stages -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Production Stages</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Stage Name</th>
                                    <th>Production Order</th>
                                    <th>Employee</th>
                                    <th>Status</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stages as $stage)
                                <tr>
                                    <td>{{ $stage->stage_name }}</td>
                                    <td>{{ $stage->productionOrder->production_number }}</td>
                                    <td>{{ $stage->employee ? $stage->employee->user->name : 'Not Assigned' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $stage->status == 'completed' ? 'success' : ($stage->status == 'in-progress' ? 'warning' : 'secondary') }}">
                                            {{ $stage->status }}
                                        </span>
                                    </td>
                                    <td>{{ $stage->start_time ? $stage->start_time->format('Y-m-d H:i') : 'Not Started' }}</td>
                                    <td>{{ $stage->end_time ? $stage->end_time->format('Y-m-d H:i') : 'Not Completed' }}</td>
                                    <td>{{ $stage->duration_minutes ?? 'Not Calculated' }} min</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No production stages found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Employee Performance -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Employee Performance</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Total Stages</th>
                                    <th>Completed Stages</th>
                                    <th>Completion Rate</th>
                                    <th>Average Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employeePerformance as $employee)
                                <tr>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->total_stages }}</td>
                                    <td>{{ $employee->completed_stages }}</td>
                                    <td>{{ $employee->completion_rate }}%</td>
                                    <td>{{ $employee->average_duration }} min</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No performance data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
