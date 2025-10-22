@extends('layouts.app')

@section('title', 'Generate Monthly Payroll')
@section('page-title', 'Generate Monthly Payroll')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">🪄 Monthly Payroll Generator</h2>
                <p class="text-gray-600 mt-1">Automatically generate payrolls for all employees or selected ones</p>
            </div>
            <a href="{{ route('payroll.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
        </div>
    </div>

    <!-- Info Card -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-sm border-2 border-blue-200 p-6 mb-6">
        <div class="flex items-start">
            <div class="bg-blue-500 text-white p-3 rounded-lg mr-4">
                <i class="fas fa-info-circle text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg text-gray-900 mb-2">How the Generator Works</h3>
                <ul class="text-gray-700 space-y-1">
                    <li>✅ Base salary is automatically calculated from employee data</li>
                    <li>✅ Overtime hours are automatically calculated from attendance records</li>
                    <li>✅ You can add bonuses and deductions later</li>
                    <li>✅ Won't create duplicate payrolls for the same employee in the same month</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <form method="POST" action="{{ route('payroll.generate.store') }}">
            @csrf

            <!-- Month Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Month & Year <span class="text-red-500">*</span>
                </label>
                <input type="month" name="month" value="{{ date('Y-m') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('month') border-red-500 @enderror" required>
                @error('month')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Payrolls will be generated for the entire month (first to last day)</p>
            </div>

            <!-- Employee Selection -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-3">
                    <label class="block text-sm font-medium text-gray-700">Select Employees</label>
                    <div class="flex gap-2">
                        <button type="button" onclick="selectAll()" class="text-sm text-blue-600 hover:text-blue-800">
                            <i class="fas fa-check-square mr-1"></i>Select All
                        </button>
                        <button type="button" onclick="deselectAll()" class="text-sm text-gray-600 hover:text-gray-800">
                            <i class="fas fa-square mr-1"></i>Deselect All
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto p-4 bg-gray-50 rounded-lg border">
                    @foreach($employees as $employee)
                    <label class="flex items-center p-3 bg-white rounded-lg border-2 border-gray-200 hover:border-blue-400 cursor-pointer transition-all">
                        <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}" class="employee-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" checked>
                        <div class="ml-3 flex-1">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold mr-2">
                                    {{ substr($employee->user->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $employee->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $employee->position }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Salary</p>
                            <p class="font-bold text-green-600">{{ number_format($employee->salary, 3) }} KWD</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    If no employees are selected, payrolls will be generated for all active employees
                </p>
            </div>

            <!-- Summary -->
            <div class="mb-6 p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg border-2 border-purple-200">
                <h3 class="font-bold text-lg text-gray-900 mb-3">Generation Summary</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Total Employees</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $employees->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Selected Employees</p>
                        <p class="text-2xl font-bold text-blue-600" id="selected-count">{{ $employees->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Expected Total Payroll</p>
                        <p class="text-2xl font-bold text-green-600" id="total-salary">{{ number_format($employees->sum('salary'), 3) }} KWD</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
                <button type="submit" class="flex-1 btn-success">
                    <i class="fas fa-magic mr-2"></i>Generate Payrolls
                </button>
                <a href="{{ route('payroll.index') }}" class="btn-secondary">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Recent Generated Info -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-history text-gray-600 mr-2"></i>
            Recent Generated Payrolls
        </h3>
        <div class="text-sm text-gray-500">
            <p>Generate new payrolls to see results here</p>
        </div>
    </div>
</div>

<script>
const checkboxes = document.querySelectorAll('.employee-checkbox');
const employees = @json($employees);

function selectAll() {
    checkboxes.forEach(cb => cb.checked = true);
    updateSummary();
}

function deselectAll() {
    checkboxes.forEach(cb => cb.checked = false);
    updateSummary();
}

function updateSummary() {
    const selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
    document.getElementById('selected-count').textContent = selectedCount;
    
    let totalSalary = 0;
    checkboxes.forEach((cb, index) => {
        if (cb.checked) {
            totalSalary += parseFloat(employees[index].salary);
        }
    });
    document.getElementById('total-salary').textContent = totalSalary.toFixed(3) + ' KWD';
}

checkboxes.forEach(cb => {
    cb.addEventListener('change', updateSummary);
});
</script>
@endsection
