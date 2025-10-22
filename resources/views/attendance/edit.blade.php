@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Edit Attendance Record</h1>
            <a href="{{ route('attendance.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Back to List
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <form action="{{ route('attendance.update', $attendance) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Employee -->
                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Employee *
                        </label>
                        <select name="employee_id" id="employee_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Employee</option>
                            @foreach(\App\Models\Employee::with('user')->get() as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id', $attendance->employee_id) == $employee->id ? 'selected' : '' }}>
                                {{ $employee->user->name }} - {{ $employee->employee_id }}
                            </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date *
                        </label>
                        <input type="date" name="date" id="date" required
                               value="{{ old('date', $attendance->date->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Check In -->
                    <div>
                        <label for="check_in" class="block text-sm font-medium text-gray-700 mb-2">
                            Check In Time
                        </label>
                        <input type="time" name="check_in" id="check_in"
                               value="{{ old('check_in', $attendance->check_in?->format('H:i')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('check_in')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Check Out -->
                    <div>
                        <label for="check_out" class="block text-sm font-medium text-gray-700 mb-2">
                            Check Out Time
                        </label>
                        <input type="time" name="check_out" id="check_out"
                               value="{{ old('check_out', $attendance->check_out?->format('H:i')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('check_out')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status *
                        </label>
                        <select name="status" id="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Status</option>
                            <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>Present</option>
                            <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="late" {{ old('status', $attendance->status) == 'late' ? 'selected' : '' }}>Late</option>
                            <option value="half_day" {{ old('status', $attendance->status) == 'half_day' ? 'selected' : '' }}>Half Day</option>
                        </select>
                        @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hours Worked -->
                    <div>
                        <label for="hours_worked" class="block text-sm font-medium text-gray-700 mb-2">
                            Hours Worked
                        </label>
                        <input type="number" name="hours_worked" id="hours_worked" step="0.01"
                               value="{{ old('hours_worked', $attendance->hours_worked) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('hours_worked')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Overtime Hours -->
                    <div>
                        <label for="overtime_hours" class="block text-sm font-medium text-gray-700 mb-2">
                            Overtime Hours
                        </label>
                        <input type="number" name="overtime_hours" id="overtime_hours" step="0.01"
                               value="{{ old('overtime_hours', $attendance->overtime_hours) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('overtime_hours')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes
                    </label>
                    <textarea name="notes" id="notes" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Enter any additional notes...">{{ old('notes', $attendance->notes) }}</textarea>
                    @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('attendance.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-calculate hours worked and overtime
function calculateHours() {
    const checkIn = document.getElementById('check_in').value;
    const checkOut = document.getElementById('check_out').value;
    
    if (checkIn && checkOut) {
        const start = new Date(`2000-01-01T${checkIn}`);
        const end = new Date(`2000-01-01T${checkOut}`);
        const diffMs = end - start;
        const diffHours = diffMs / (1000 * 60 * 60);
        
        if (diffHours > 0) {
            const regularHours = Math.min(diffHours, 8);
            const overtimeHours = Math.max(0, diffHours - 8);
            
            document.getElementById('hours_worked').value = regularHours.toFixed(2);
            document.getElementById('overtime_hours').value = overtimeHours.toFixed(2);
        }
    }
}

document.getElementById('check_in').addEventListener('change', calculateHours);
document.getElementById('check_out').addEventListener('change', calculateHours);
</script>
@endsection
