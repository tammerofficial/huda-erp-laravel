@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Edit Quality Check #{{ $qualityCheck->id }}</h1>
            <a href="{{ route('quality-checks.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Back to List
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
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
                            <option value="{{ $order->id }}" {{ old('production_order_id', $qualityCheck->production_order_id) == $order->id ? 'selected' : '' }}>
                                #{{ $order->id }} - {{ $order->product->name }} ({{ $order->quantity }} pcs)
                            </option>
                            @endforeach
                        </select>
                        @error('production_order_id')
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
                            <option value="{{ $product->id }}" {{ old('product_id', $qualityCheck->product_id) == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('product_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                            <option value="{{ $inspector->id }}" {{ old('inspector_id', $qualityCheck->inspector_id) == $inspector->id ? 'selected' : '' }}>
                                {{ $inspector->user->name }} - {{ $inspector->position }}
                            </option>
                            @endforeach
                        </select>
                        @error('inspector_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Inspection Result *
                        </label>
                        <select name="status" id="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Result</option>
                            <option value="passed" {{ old('status', $qualityCheck->status) == 'passed' ? 'selected' : '' }}>✅ Passed</option>
                            <option value="failed" {{ old('status', $qualityCheck->status) == 'failed' ? 'selected' : '' }}>❌ Failed</option>
                        </select>
                        @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Items Passed -->
                    <div>
                        <label for="items_passed" class="block text-sm font-medium text-gray-700 mb-2">
                            Items Passed
                        </label>
                        <input type="number" name="items_passed" id="items_passed"
                               value="{{ old('items_passed', $qualityCheck->items_passed) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('items_passed')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Items Failed -->
                    <div>
                        <label for="items_failed" class="block text-sm font-medium text-gray-700 mb-2">
                            Items Failed
                        </label>
                        <input type="number" name="items_failed" id="items_failed"
                               value="{{ old('items_failed', $qualityCheck->items_failed) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('items_failed')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Defects -->
                <div class="mt-6">
                    <label for="defects" class="block text-sm font-medium text-gray-700 mb-2">
                        Defects Found
                    </label>
                    <div id="defects-container">
                        @if(old('defects', $qualityCheck->defects))
                            @foreach(old('defects', $qualityCheck->defects) as $index => $defect)
                            <div class="flex items-center space-x-2 mb-2">
                                <input type="text" name="defects[]" value="{{ $defect }}" placeholder="Defect type"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button type="button" onclick="removeDefect(this)" class="text-red-600 hover:text-red-800">
                                    ❌
                                </button>
                            </div>
                            @endforeach
                        @else
                            <div class="flex items-center space-x-2 mb-2">
                                <input type="text" name="defects[]" placeholder="Defect type"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button type="button" onclick="removeDefect(this)" class="text-red-600 hover:text-red-800">
                                    ❌
                                </button>
                            </div>
                        @endif
                    </div>
                    <button type="button" onclick="addDefect()" class="mt-2 text-blue-600 hover:text-blue-800">
                        + Add Defect
                    </button>
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes
                    </label>
                    <textarea name="notes" id="notes" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes', $qualityCheck->notes) }}</textarea>
                    @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('quality-checks.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Quality Check
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addDefect() {
    const container = document.getElementById('defects-container');
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2 mb-2';
    div.innerHTML = `
        <input type="text" name="defects[]" placeholder="Defect type"
               class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="button" onclick="removeDefect(this)" class="text-red-600 hover:text-red-800">
            ❌
        </button>
    `;
    container.appendChild(div);
}

function removeDefect(button) {
    button.parentElement.remove();
}
</script>
@endsection
