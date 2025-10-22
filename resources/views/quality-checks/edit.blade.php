@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">🔍 Edit Quality Check</h1>
        <div class="flex gap-4">
            <a href="{{ route('quality-checks.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Back to Quality Checks
            </a>
            <a href="{{ route('quality-checks.show', $qualityCheck) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                View Details
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('quality-checks.update', $qualityCheck) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Production Order -->
                <div>
                    <label for="production_order_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Production Order *
                    </label>
                    <select name="production_order_id" id="production_order_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Production Order</option>
                        @foreach($productionOrders as $order)
                            <option value="{{ $order->id }}" 
                                    {{ old('production_order_id', $qualityCheck->production_order_id) == $order->id ? 'selected' : '' }}>
                                #{{ $order->id }} - {{ $order->product->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('production_order_id')
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
                        @foreach($productionOrders as $order)
                            @if($order->product)
                                <option value="{{ $order->product->id }}" 
                                        {{ old('product_id', $qualityCheck->product_id) == $order->product->id ? 'selected' : '' }}>
                                    {{ $order->product->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Inspector -->
                <div>
                    <label for="inspector_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Inspector *
                    </label>
                    <select name="inspector_id" id="inspector_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Inspector</option>
                        @foreach($inspectors as $inspector)
                            <option value="{{ $inspector->id }}" 
                                    {{ old('inspector_id', $qualityCheck->inspector_id) == $inspector->id ? 'selected' : '' }}>
                                {{ $inspector->user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('inspector_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
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
                        <option value="passed" {{ old('status', $qualityCheck->status) == 'passed' ? 'selected' : '' }}>Passed</option>
                        <option value="failed" {{ old('status', $qualityCheck->status) == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Items Checked -->
                <div>
                    <label for="items_checked" class="block text-sm font-medium text-gray-700 mb-2">
                        Items Checked *
                    </label>
                    <input type="number" name="items_checked" id="items_checked" required
                           value="{{ old('items_checked', $qualityCheck->items_checked) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('items_checked')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Items Passed -->
                <div>
                    <label for="items_passed" class="block text-sm font-medium text-gray-700 mb-2">
                        Items Passed *
                    </label>
                    <input type="number" name="items_passed" id="items_passed" required
                           value="{{ old('items_passed', $qualityCheck->items_passed) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('items_passed')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Items Failed -->
                <div>
                    <label for="items_failed" class="block text-sm font-medium text-gray-700 mb-2">
                        Items Failed *
                    </label>
                    <input type="number" name="items_failed" id="items_failed" required
                           value="{{ old('items_failed', $qualityCheck->items_failed) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('items_failed')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Defects -->
                <div class="md:col-span-2">
                    <label for="defects" class="block text-sm font-medium text-gray-700 mb-2">
                        Defects (JSON format)
                    </label>
                    <textarea name="defects" id="defects" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder='{"defect1": "description", "defect2": "description"}'>{{ old('defects', $qualityCheck->defects ? json_encode($qualityCheck->defects, JSON_PRETTY_PRINT) : '') }}</textarea>
                    @error('defects')
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
                              placeholder="Additional notes about the quality check...">{{ old('notes', $qualityCheck->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Update Quality Check
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-update product when production order changes
document.getElementById('production_order_id').addEventListener('change', function() {
    const productionOrderId = this.value;
    const productSelect = document.getElementById('product_id');
    
    if (productionOrderId) {
        // Find the selected production order and update product
        const selectedOption = this.options[this.selectedIndex];
        const productName = selectedOption.text.split(' - ')[1];
        
        // Update product select
        for (let option of productSelect.options) {
            if (option.text.includes(productName)) {
                option.selected = true;
                break;
            }
        }
    }
});
</script>
@endsection