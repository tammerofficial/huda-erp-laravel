@extends('layouts.app')

@section('title', 'Create Payroll')
@section('page-title', 'Create Payroll')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">🧾 Create New Payroll</h2>
                <p class="text-gray-600 mt-1">Add payroll entry for an employee</p>
            </div>
            <a href="{{ route('payroll.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <form method="POST" action="{{ route('payroll.store') }}">
            @csrf

            <!-- Employee Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Employee <span class="text-red-500">*</span>
                </label>
                <select name="employee_id" id="employee_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('employee_id') border-red-500 @enderror" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" 
                                data-salary="{{ $employee->salary }}"
                                data-overtime-rate="{{ $employee->overtime_rate ?? 0 }}"
                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->user->name }} - {{ $employee->position }} ({{ number_format($employee->salary, 3) }} KWD)
                        </option>
                    @endforeach
                </select>
                @error('employee_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Period -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Period Start <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="period_start" value="{{ old('period_start') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('period_start') border-red-500 @enderror" required>
                    @error('period_start')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Period End <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="period_end" value="{{ old('period_end') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('period_end') border-red-500 @enderror" required>
                    @error('period_end')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Salary Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Base Salary (KWD) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="base_salary" id="base_salary" step="0.001" value="{{ old('base_salary') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('base_salary') border-red-500 @enderror" required onchange="calculateTotal()">
                    @error('base_salary')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Overtime Hours</label>
                    <input type="number" name="overtime_hours" id="overtime_hours" step="0.01" value="{{ old('overtime_hours', 0) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" onchange="calculateOvertime()">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Overtime Amount (KWD)</label>
                    <input type="number" name="overtime_amount" id="overtime_amount" step="0.001" value="{{ old('overtime_amount', 0) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" onchange="calculateTotal()">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bonuses (KWD)</label>
                    <input type="number" name="bonuses" id="bonuses" step="0.001" value="{{ old('bonuses', 0) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" onchange="calculateTotal()">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deductions (KWD)</label>
                <input type="number" name="deductions" id="deductions" step="0.001" value="{{ old('deductions', 0) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" onchange="calculateTotal()">
            </div>

            <!-- Total Display -->
            <div class="mb-6 p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border-2 border-green-200">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-700">Total Amount:</span>
                    <span id="total_display" class="text-3xl font-bold text-green-600">0.000 KWD</span>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Date</label>
                    <input type="date" name="payment_date" value="{{ old('payment_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Payment Method</option>
                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                    </select>
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
                <button type="submit" class="btn-success">
                    <i class="fas fa-save mr-2"></i>Create Payroll
                </button>
                <a href="{{ route('payroll.index') }}" class="btn-secondary">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-fill salary when employee is selected
document.getElementById('employee_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const salary = selectedOption.getAttribute('data-salary');
    if (salary) {
        document.getElementById('base_salary').value = parseFloat(salary).toFixed(3);
        calculateTotal();
    }
});

// Calculate overtime amount
function calculateOvertime() {
    const employeeSelect = document.getElementById('employee_id');
    const selectedOption = employeeSelect.options[employeeSelect.selectedIndex];
    const overtimeRate = parseFloat(selectedOption.getAttribute('data-overtime-rate') || 0);
    const overtimeHours = parseFloat(document.getElementById('overtime_hours').value || 0);
    
    const overtimeAmount = overtimeHours * overtimeRate;
    document.getElementById('overtime_amount').value = overtimeAmount.toFixed(3);
    calculateTotal();
}

// Calculate total
function calculateTotal() {
    const baseSalary = parseFloat(document.getElementById('base_salary').value || 0);
    const overtimeAmount = parseFloat(document.getElementById('overtime_amount').value || 0);
    const bonuses = parseFloat(document.getElementById('bonuses').value || 0);
    const deductions = parseFloat(document.getElementById('deductions').value || 0);
    
    const total = baseSalary + overtimeAmount + bonuses - deductions;
    document.getElementById('total_display').textContent = total.toFixed(3) + ' KWD';
}

// Initial calculation
calculateTotal();
</script>
@endsection
