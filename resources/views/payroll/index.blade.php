@extends('layouts.app')

@section('title', 'Payroll Management')
@section('page-title', 'Payroll Management')

@section('content')
<!-- Header Section -->
<div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">💰 Payroll Management</h2>
            <p class="text-gray-600 mt-1">Comprehensive payroll management system</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('payroll.generate') }}" class="btn-primary">
                <i class="fas fa-magic mr-2"></i>Generate Monthly Payroll
            </a>
            <a href="{{ route('payroll.create') }}" class="btn-success">
                <i class="fas fa-plus mr-2"></i>Create Payroll
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg shadow-sm p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm mb-1">Total Paid</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_paid'], 3) }}</p>
                <p class="text-xs text-green-100">KWD</p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg">
                <i class="fas fa-check-circle text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg shadow-sm p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm mb-1">Pending Approval</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_approved'], 3) }}</p>
                <p class="text-xs text-yellow-100">KWD</p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg">
                <i class="fas fa-clock text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-sm p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm mb-1">Drafts</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_pending'], 3) }}</p>
                <p class="text-xs text-blue-100">KWD</p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg">
                <i class="fas fa-file-alt text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg shadow-sm p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm mb-1">Total Employees</p>
                <p class="text-3xl font-bold">{{ $stats['employee_count'] }}</p>
                <p class="text-xs text-purple-100">Active</p>
            </div>
            <div class="bg-white/20 p-3 rounded-lg">
                <i class="fas fa-users text-3xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Chart -->
<div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
        <i class="fas fa-chart-line text-blue-600 mr-2"></i>
        Monthly Payroll Trend (Last 12 Months)
    </h3>
    <canvas id="monthlyChart" height="80"></canvas>
</div>

<!-- Filters Section -->
<div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
    <form method="GET" action="{{ route('payroll.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Month</label>
            <input type="month" name="month" value="{{ request('month') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Employee</label>
            <select name="employee_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">All Employees</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="md:col-span-5 flex gap-3">
            <button type="submit" class="btn-primary">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
            <a href="{{ route('payroll.index') }}" class="btn-secondary">
                <i class="fas fa-redo mr-2"></i>Reset
            </a>
        </div>
    </form>
</div>

<!-- Payrolls Table -->
<div class="bg-white rounded-lg shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Base Salary</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overtime</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bonuses</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deductions</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($payrolls as $payroll)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold">
                                {{ substr($payroll->employee->user->name, 0, 2) }}
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $payroll->employee->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $payroll->employee->position }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $payroll->period_start->format('M d, Y') }}</div>
                        <div class="text-sm text-gray-500">to {{ $payroll->period_end->format('M d, Y') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        KWD {{ number_format($payroll->base_salary, 3) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($payroll->overtime_hours > 0)
                            <div class="text-sm text-gray-900">{{ $payroll->overtime_hours }} hrs</div>
                            <div class="text-xs text-gray-500">KWD {{ number_format($payroll->overtime_amount, 3) }}</div>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        KWD {{ number_format($payroll->bonuses, 3) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        KWD {{ number_format($payroll->deductions, 3) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-lg font-bold text-green-600">KWD {{ number_format($payroll->total_amount, 3) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($payroll->status === 'draft')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Draft
                            </span>
                        @elseif($payroll->status === 'approved')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Approved
                            </span>
                        @elseif($payroll->status === 'paid')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Paid
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($payroll->payment_date)
                            <div class="text-sm text-gray-900">{{ $payroll->payment_date->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $payroll->payment_method)) }}</div>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex gap-2">
                            <a href="{{ route('payroll.show', $payroll) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($payroll->status !== 'paid')
                            <a href="{{ route('payroll.edit', $payroll) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                            <p class="text-gray-500 text-lg">No payrolls found</p>
                            <a href="{{ route('payroll.create') }}" class="mt-4 btn-primary">
                                <i class="fas fa-plus mr-2"></i>Create First Payroll
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($payrolls->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $payrolls->links() }}
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const monthlyData = @json($monthlyData);
const ctx = document.getElementById('monthlyChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: monthlyData.map(d => d.month),
        datasets: [{
            label: 'Total Payroll (KWD)',
            data: monthlyData.map(d => d.total),
            backgroundColor: 'rgba(59, 130, 246, 0.5)',
            borderColor: 'rgb(59, 130, 246)',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'KWD ' + context.parsed.y.toFixed(3);
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'KWD ' + value.toFixed(0);
                    }
                }
            }
        }
    }
});
</script>
@endsection
