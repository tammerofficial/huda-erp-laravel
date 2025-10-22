@extends('layouts.app')

@section('title', 'Payroll Details')
@section('page-title', 'Payroll Details')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">📋 Payroll Details</h2>
                <p class="text-gray-600 mt-1">Payroll ID: #{{ $payroll->id }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('payroll.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
                @if($payroll->status === 'draft')
                <form method="POST" action="{{ route('payroll.approve', $payroll) }}" class="inline">
                    @csrf
                    <button type="submit" class="btn-success">
                        <i class="fas fa-check mr-2"></i>Approve Payroll
                    </button>
                </form>
                @endif
                
                @if($payroll->status !== 'paid')
                <button onclick="showPaymentModal()" class="btn-primary">
                    <i class="fas fa-dollar-sign mr-2"></i>Mark as Paid
                </button>
                <a href="{{ route('payroll.edit', $payroll) }}" class="btn-warning">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Employee Info -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user text-blue-600 mr-2"></i>
                    Employee Information
                </h3>
                <div class="flex items-center mb-4">
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold mr-4">
                        {{ substr($payroll->employee->user->name, 0, 2) }}
                    </div>
                    <div>
                        <h4 class="text-2xl font-bold text-gray-900">{{ $payroll->employee->user->name }}</h4>
                        <p class="text-gray-600">{{ $payroll->employee->position }}</p>
                        <p class="text-sm text-gray-500">{{ $payroll->employee->department }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-semibold">{{ $payroll->employee->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Employee ID</p>
                        <p class="font-semibold">{{ $payroll->employee->employee_id }}</p>
                    </div>
                </div>
            </div>

            <!-- Salary Breakdown -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-calculator text-green-600 mr-2"></i>
                    Salary Breakdown
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                        <span class="text-gray-700 font-medium">Base Salary</span>
                        <span class="font-bold text-lg">{{ number_format($payroll->base_salary, 3) }} KWD</span>
                    </div>
                    
                    @if($payroll->overtime_hours > 0)
                    <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg">
                        <div>
                            <span class="text-gray-700 font-medium">Overtime Hours</span>
                            <p class="text-sm text-gray-500">{{ $payroll->overtime_hours }} hours</p>
                        </div>
                        <span class="font-bold text-lg text-blue-600">+{{ number_format($payroll->overtime_amount, 3) }} KWD</span>
                    </div>
                    @endif
                    
                    @if($payroll->bonuses > 0)
                    <div class="flex justify-between items-center p-4 bg-green-50 rounded-lg">
                        <span class="text-gray-700 font-medium">Bonuses & Allowances</span>
                        <span class="font-bold text-lg text-green-600">+{{ number_format($payroll->bonuses, 3) }} KWD</span>
                    </div>
                    @endif
                    
                    @if($payroll->deductions > 0)
                    <div class="flex justify-between items-center p-4 bg-red-50 rounded-lg">
                        <span class="text-gray-700 font-medium">Deductions</span>
                        <span class="font-bold text-lg text-red-600">-{{ number_format($payroll->deductions, 3) }} KWD</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-center p-6 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg text-white mt-4">
                        <span class="text-lg font-semibold">Net Salary</span>
                        <span class="text-3xl font-bold">{{ number_format($payroll->total_amount, 3) }} KWD</span>
                    </div>
                </div>
            </div>

            <!-- Work Logs -->
            @if($workLogs->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-clock text-purple-600 mr-2"></i>
                    Attendance Records (Period)
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check In</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check Out</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hours Worked</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($workLogs as $log)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $log->check_in->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $log->check_in->format('h:i A') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $log->check_out ? $log->check_out->format('h:i A') : '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($log->hours_worked)
                                        <span class="font-semibold">{{ number_format($log->hours_worked, 2) }} hrs</span>
                                        @if($log->hours_worked > 8)
                                            <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Overtime</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                <div class="text-center py-4">
                    @if($payroll->status === 'draft')
                        <span class="inline-flex px-4 py-2 text-lg font-semibold rounded-full bg-yellow-100 text-yellow-800">Draft</span>
                    @elseif($payroll->status === 'approved')
                        <span class="inline-flex px-4 py-2 text-lg font-semibold rounded-full bg-blue-100 text-blue-800">Approved</span>
                    @elseif($payroll->status === 'paid')
                        <span class="inline-flex px-4 py-2 text-lg font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                    @endif
                </div>
            </div>

            <!-- Period -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payroll Period</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">From</p>
                        <p class="font-semibold text-lg">{{ $payroll->period_start->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">To</p>
                        <p class="font-semibold text-lg">{{ $payroll->period_end->format('M d, Y') }}</p>
                    </div>
                    <div class="pt-3 border-t">
                        <p class="text-sm text-gray-500">Duration</p>
                        <p class="font-semibold">{{ $payroll->period_start->diffInDays($payroll->period_end) + 1 }} days</p>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            @if($payroll->payment_date)
            <div class="bg-white rounded-lg shadow-sm border p-6 bg-gradient-to-br from-green-50 to-emerald-50">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Payment Date</p>
                        <p class="font-semibold text-lg">{{ $payroll->payment_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Payment Method</p>
                        <p class="font-semibold">{{ ucfirst(str_replace('_', ' ', $payroll->payment_method)) }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($payroll->notes)
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
                <p class="text-gray-700">{{ $payroll->notes }}</p>
            </div>
            @endif

            <!-- System Info -->
            <div class="bg-white rounded-lg shadow-sm border p-6 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">System Information</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <p class="text-gray-500">Created By</p>
                        <p class="font-semibold">{{ $payroll->createdBy->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Created At</p>
                        <p class="font-semibold">{{ $payroll->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @if($payroll->updated_at != $payroll->created_at)
                    <div>
                        <p class="text-gray-500">Last Updated</p>
                        <p class="font-semibold">{{ $payroll->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Mark Payroll as Paid</h3>
        <form method="POST" action="{{ route('payroll.mark-paid', $payroll) }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Date <span class="text-red-500">*</span></label>
                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method <span class="text-red-500">*</span></label>
                <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="cash">Cash</option>
                    <option value="cheque">Cheque</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 btn-success">
                    <i class="fas fa-check mr-2"></i>Confirm Payment
                </button>
                <button type="button" onclick="hidePaymentModal()" class="flex-1 btn-secondary">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showPaymentModal() {
    document.getElementById('paymentModal').classList.remove('hidden');
    document.getElementById('paymentModal').classList.add('flex');
}

function hidePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
    document.getElementById('paymentModal').classList.remove('flex');
}
</script>
@endsection
