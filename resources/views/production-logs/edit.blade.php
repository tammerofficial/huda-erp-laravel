@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">📝 Edit Production Log</h1>
        <div class="flex gap-4">
            <a href="{{ route('production-logs.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Back to Production Logs
            </a>
            <a href="{{ route('production-logs.show', $productionLog) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                View Details
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('production-logs.update', $productionLog) }}" method="POST">
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
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" 
                                    {{ old('employee_id', $productionLog->employee_id) == $employee->id ? 'selected' : '' }}>
                                {{ $employee->user->name }} - {{ $employee->position }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Production Stage -->
                <div>
                    <label for="production_stage_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Production Stage *
                    </label>
                    <select name="production_stage_id" id="production_stage_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Production Stage</option>
                        @foreach($stages as $stage)
                            <option value="{{ $stage->id }}" 
                                    {{ old('production_stage_id', $productionLog->production_stage_id) == $stage->id ? 'selected' : '' }}>
                                {{ $stage->stage_name }} - Order #{{ $stage->productionOrder->id ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('production_stage_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Product -->
                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Product *
                    </label>
                    <select name="product_id" id="product_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    {{ old('product_id', $productionLog->product_id) == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} - {{ $product->sku }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pieces Completed -->
                <div>
                    <label for="pieces_completed" class="block text-sm font-medium text-gray-700 mb-2">
                        Pieces Completed *
                    </label>
                    <input type="number" name="pieces_completed" id="pieces_completed" required min="1"
                           value="{{ old('pieces_completed', $productionLog->pieces_completed) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('pieces_completed')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rate Per Piece -->
                <div>
                    <label for="rate_per_piece" class="block text-sm font-medium text-gray-700 mb-2">
                        Rate Per Piece (KWD)
                    </label>
                    <input type="number" name="rate_per_piece" id="rate_per_piece" step="0.001" min="0"
                           value="{{ old('rate_per_piece', $productionLog->rate_per_piece) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('rate_per_piece')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Time -->
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Start Time *
                    </label>
                    <input type="datetime-local" name="start_time" id="start_time" required
                           value="{{ old('start_time', $productionLog->start_time->format('Y-m-d\TH:i')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('start_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Time -->
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                        End Time
                    </label>
                    <input type="datetime-local" name="end_time" id="end_time"
                           value="{{ old('end_time', $productionLog->end_time ? $productionLog->end_time->format('Y-m-d\TH:i') : '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('end_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Expected Duration -->
                <div>
                    <label for="expected_duration" class="block text-sm font-medium text-gray-700 mb-2">
                        Expected Duration (minutes)
                    </label>
                    <input type="number" name="expected_duration" id="expected_duration" step="0.01" min="0"
                           value="{{ old('expected_duration', $productionLog->expected_duration) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('expected_duration')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quality Status -->
                <div>
                    <label for="quality_status" class="block text-sm font-medium text-gray-700 mb-2">
                        Quality Status
                    </label>
                    <select name="quality_status" id="quality_status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending" {{ old('quality_status', $productionLog->quality_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('quality_status', $productionLog->quality_status) == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('quality_status', $productionLog->quality_status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('quality_status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes
                    </label>
                    <textarea name="notes" id="notes" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Additional notes about the production log...">{{ old('notes', $productionLog->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Update Production Log
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-calculate earnings when pieces completed or rate per piece changes
function calculateEarnings() {
    const pieces = parseFloat(document.getElementById('pieces_completed').value) || 0;
    const rate = parseFloat(document.getElementById('rate_per_piece').value) || 0;
    const earnings = pieces * rate;
    
    // Show calculated earnings (read-only display)
    let earningsDisplay = document.getElementById('earnings-display');
    if (!earningsDisplay) {
        earningsDisplay = document.createElement('div');
        earningsDisplay.id = 'earnings-display';
        earningsDisplay.className = 'mt-2 text-sm text-gray-600';
        document.getElementById('rate_per_piece').parentNode.appendChild(earningsDisplay);
    }
    earningsDisplay.textContent = `Calculated Earnings: ${earnings.toFixed(3)} KWD`;
}

document.getElementById('pieces_completed').addEventListener('input', calculateEarnings);
document.getElementById('rate_per_piece').addEventListener('input', calculateEarnings);

// Calculate on page load
calculateEarnings();
</script>
@endsection