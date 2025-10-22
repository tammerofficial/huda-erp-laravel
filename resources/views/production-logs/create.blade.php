@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Create Production Log</h1>
            <a href="{{ route('production-logs.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Back to List
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <form action="{{ route('production-logs.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Employee -->
                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Employee *
                        </label>
                        <select name="employee_id" id="employee_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Employee</option>
                            @foreach(\App\Models\Employee::with('user')->where('employment_status', 'active')->get() as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->user->name }} - {{ $employee->employee_id }}
                            </option>
                            @endforeach
                        </select>
                        @error('employee_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                            @foreach(\App\Models\ProductionStage::with('productionOrder.product')->get() as $stage)
                            <option value="{{ $stage->id }}" {{ old('production_stage_id') == $stage->id ? 'selected' : '' }}>
                                {{ $stage->stage_name }} - {{ $stage->productionOrder->product->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('production_stage_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                            @foreach(\App\Models\Product::all() as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('product_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pieces Completed -->
                    <div>
                        <label for="pieces_completed" class="block text-sm font-medium text-gray-700 mb-2">
                            Pieces Completed *
                        </label>
                        <input type="number" name="pieces_completed" id="pieces_completed" required
                               value="{{ old('pieces_completed') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('pieces_completed')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rate Per Piece -->
                    <div>
                        <label for="rate_per_piece" class="block text-sm font-medium text-gray-700 mb-2">
                            Rate Per Piece (KWD)
                        </label>
                        <input type="number" name="rate_per_piece" id="rate_per_piece" step="0.001"
                               value="{{ old('rate_per_piece') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('rate_per_piece')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Start Time *
                        </label>
                        <input type="datetime-local" name="start_time" id="start_time" required
                               value="{{ old('start_time') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('start_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                            End Time
                        </label>
                        <input type="datetime-local" name="end_time" id="end_time"
                               value="{{ old('end_time') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('end_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expected Duration -->
                    <div>
                        <label for="expected_duration" class="block text-sm font-medium text-gray-700 mb-2">
                            Expected Duration (minutes)
                        </label>
                        <input type="number" name="expected_duration" id="expected_duration" step="0.01"
                               value="{{ old('expected_duration') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('expected_duration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quality Status -->
                    <div>
                        <label for="quality_status" class="block text-sm font-medium text-gray-700 mb-2">
                            Quality Status
                        </label>
                        <select name="quality_status" id="quality_status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pending" {{ old('quality_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ old('quality_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ old('quality_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        @error('quality_status')
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
                              placeholder="Enter any additional notes about the production...">{{ old('notes') }}</textarea>
                    @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('production-logs.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Create Production Log
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-calculate duration and earnings
function calculateProduction() {
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    const piecesCompleted = parseInt(document.getElementById('pieces_completed').value) || 0;
    const ratePerPiece = parseFloat(document.getElementById('rate_per_piece').value) || 0;
    
    if (startTime && endTime) {
        const start = new Date(startTime);
        const end = new Date(endTime);
        const diffMs = end - start;
        const diffMinutes = diffMs / (1000 * 60);
        
        // Update duration (this would be handled by the model in real implementation)
        console.log('Duration:', diffMinutes, 'minutes');
    }
    
    if (piecesCompleted && ratePerPiece) {
        const earnings = piecesCompleted * ratePerPiece;
        console.log('Earnings:', earnings, 'KWD');
    }
}

document.getElementById('start_time').addEventListener('change', calculateProduction);
document.getElementById('end_time').addEventListener('change', calculateProduction);
document.getElementById('pieces_completed').addEventListener('change', calculateProduction);
document.getElementById('rate_per_piece').addEventListener('change', calculateProduction);
</script>
@endsection
