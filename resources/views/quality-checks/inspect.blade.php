@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Inspect Production Order #{{ $productionOrder->id }}</h1>
            <a href="{{ route('productions.show', $productionOrder) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Back to Order
            </a>
        </div>

        <!-- Production Order Info -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Production Order Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Product</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $productionOrder->product->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Quantity</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $productionOrder->quantity }} pieces</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Status</label>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Awaiting Quality Check
                    </span>
                </div>
            </div>
        </div>

        <!-- Inspection Form -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form action="{{ route('quality-checks.submit-inspection', $productionOrder) }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Inspector -->
                    <div>
                        <label for="inspector_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Inspector *
                        </label>
                        <select name="inspector_id" id="inspector_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Inspector</option>
                            @foreach($inspectors as $inspector)
                            <option value="{{ $inspector->id }}" {{ old('inspector_id') == $inspector->id ? 'selected' : '' }}>
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
                            <option value="passed" {{ old('status') == 'passed' ? 'selected' : '' }}>✅ Passed</option>
                            <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>❌ Failed</option>
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
                               value="{{ old('items_checked', $productionOrder->quantity) }}"
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
                               value="{{ old('items_passed') }}"
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
                               value="{{ old('items_failed') }}"
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
                        <div class="flex items-center space-x-2 mb-2">
                            <input type="text" name="defects[]" placeholder="Defect type"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="button" onclick="removeDefect(this)" class="text-red-600 hover:text-red-800">
                                ❌
                            </button>
                        </div>
                    </div>
                    <button type="button" onclick="addDefect()" class="mt-2 text-blue-600 hover:text-blue-800">
                        + Add Defect
                    </button>
                </div>

                <!-- Notes -->
                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Inspection Notes
                    </label>
                    <textarea name="notes" id="notes" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Enter any additional notes about the inspection...">{{ old('notes') }}</textarea>
                    @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end space-x-4">
                    <a href="{{ route('productions.show', $productionOrder) }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Submit Inspection Results
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

// Auto-calculate items passed/failed based on status
document.getElementById('status').addEventListener('change', function() {
    const status = this.value;
    const itemsChecked = parseInt(document.getElementById('items_checked').value) || 0;
    const itemsPassed = document.getElementById('items_passed');
    const itemsFailed = document.getElementById('items_failed');
    
    if (status === 'passed') {
        itemsPassed.value = itemsChecked;
        itemsFailed.value = 0;
    } else if (status === 'failed') {
        itemsPassed.value = 0;
        itemsFailed.value = itemsChecked;
    }
});
</script>
@endsection
