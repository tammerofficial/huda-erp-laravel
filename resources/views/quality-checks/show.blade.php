@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">🔍 Quality Check Details</h1>
        <div class="flex gap-4">
            <a href="{{ route('quality-checks.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Back to Quality Checks
            </a>
            <a href="{{ route('quality-checks.edit', $qualityCheck) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Quality Check Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Production Order</label>
                        <p class="text-lg font-semibold text-gray-900">
                            #{{ $qualityCheck->productionOrder->id ?? 'N/A' }}
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Product</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $qualityCheck->product->name ?? 'N/A' }}
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Inspector</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $qualityCheck->inspector->user->name ?? 'N/A' }}
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <div class="mt-1">
                            @if($qualityCheck->status === 'passed')
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    ✅ Passed
                                </span>
                            @elseif($qualityCheck->status === 'failed')
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    ❌ Failed
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    ⏳ Pending
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Inspection Date</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $qualityCheck->inspection_date->format('M d, Y H:i') }}
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Pass Rate</label>
                        <p class="text-lg font-semibold text-gray-900">
                            @if($qualityCheck->items_checked > 0)
                                {{ round(($qualityCheck->items_passed / $qualityCheck->items_checked) * 100, 1) }}%
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quality Metrics -->
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-xl font-semibold mb-4">Quality Metrics</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $qualityCheck->items_checked }}</div>
                        <div class="text-sm text-gray-500">Items Checked</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $qualityCheck->items_passed }}</div>
                        <div class="text-sm text-gray-500">Items Passed</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold text-red-600">{{ $qualityCheck->items_failed }}</div>
                        <div class="text-sm text-gray-500">Items Failed</div>
                    </div>
                </div>
            </div>

            <!-- Defects -->
            @if($qualityCheck->defects && count($qualityCheck->defects) > 0)
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-xl font-semibold mb-4">Defects Found</h2>
                
                <div class="space-y-3">
                    @foreach($qualityCheck->defects as $defect => $description)
                    <div class="border-l-4 border-red-500 pl-4">
                        <div class="font-semibold text-red-800">{{ $defect }}</div>
                        <div class="text-gray-600">{{ $description }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Notes -->
            @if($qualityCheck->notes)
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-xl font-semibold mb-4">Notes</h2>
                <p class="text-gray-700">{{ $qualityCheck->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('quality-checks.edit', $qualityCheck) }}" 
                       class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-center block">
                        Edit Quality Check
                    </a>
                    
                    <form action="{{ route('quality-checks.destroy', $qualityCheck) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this quality check?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                            Delete Quality Check
                        </button>
                    </form>
                </div>
            </div>

            <!-- Production Order Info -->
            @if($qualityCheck->productionOrder)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Production Order</h3>
                <div class="space-y-2">
                    <div>
                        <span class="text-sm text-gray-500">Order ID:</span>
                        <span class="font-semibold">#{{ $qualityCheck->productionOrder->id }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Status:</span>
                        <span class="font-semibold">{{ ucfirst($qualityCheck->productionOrder->status) }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Quantity:</span>
                        <span class="font-semibold">{{ $qualityCheck->productionOrder->quantity_to_produce }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Inspector Info -->
            @if($qualityCheck->inspector)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Inspector</h3>
                <div class="space-y-2">
                    <div>
                        <span class="text-sm text-gray-500">Name:</span>
                        <span class="font-semibold">{{ $qualityCheck->inspector->user->name }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Position:</span>
                        <span class="font-semibold">{{ $qualityCheck->inspector->position }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Department:</span>
                        <span class="font-semibold">{{ $qualityCheck->inspector->department }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection