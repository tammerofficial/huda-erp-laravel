@extends('layouts.app')

@section('title', 'Production Order Details')
@section('page-title', 'Production Order Details')

@section('content')
<div x-data="productionDetails()">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">🏭 Production Order: {{ $productionOrder->production_number }}</h2>
                    <p class="text-gray-600 mt-1">Complete production order information and stages</p>
                </div>
                <div class="flex space-x-3 space-x-reverse">
                    <a href="{{ route('productions.edit', $productionOrder) }}" 
                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    <a href="{{ route('productions.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Production Order Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Order Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                    Production Order Information
                </h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Production Number:</span>
                        <span class="text-gray-900 font-mono">{{ $productionOrder->production_number }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Order:</span>
                        <span class="text-gray-900">{{ $productionOrder->order->order_number }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Customer:</span>
                        <span class="text-gray-900">{{ $productionOrder->order->customer->name }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Product:</span>
                        <span class="text-gray-900">{{ $productionOrder->product->name }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Quantity:</span>
                        <span class="text-gray-900 font-semibold">{{ $productionOrder->quantity }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Status:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($productionOrder->status == 'completed') bg-green-100 text-green-800
                            @elseif($productionOrder->status == 'cancelled') bg-red-100 text-red-800
                            @elseif($productionOrder->status == 'in-progress') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst(str_replace('-', ' ', $productionOrder->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Dates & Costs -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-calendar mr-2 text-green-600"></i>
                    Dates & Costs
                </h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Start Date:</span>
                        <span class="text-gray-900">{{ $productionOrder->start_date ? $productionOrder->start_date->format('M d, Y') : 'Not set' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">End Date:</span>
                        <span class="text-gray-900">{{ $productionOrder->end_date ? $productionOrder->end_date->format('M d, Y') : 'Not set' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Due Date:</span>
                        <span class="text-gray-900">{{ $productionOrder->due_date ? $productionOrder->due_date->format('M d, Y') : 'Not set' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Priority:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($productionOrder->priority == 'urgent') bg-red-100 text-red-800
                            @elseif($productionOrder->priority == 'high') bg-orange-100 text-orange-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ ucfirst($productionOrder->priority) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Estimated Cost:</span>
                        <span class="text-gray-900 font-mono">{{ $productionOrder->estimated_cost ? number_format($productionOrder->estimated_cost, 2) . ' KWD' : 'Not specified' }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-700">Actual Cost:</span>
                        <span class="text-gray-900 font-mono">{{ $productionOrder->actual_cost ? number_format($productionOrder->actual_cost, 2) . ' KWD' : 'Not specified' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($productionOrder->notes)
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-comment mr-2 text-purple-600"></i>
                Notes
            </h3>
            <div class="prose max-w-none">
                <p class="text-gray-900 leading-relaxed">{{ $productionOrder->notes }}</p>
            </div>
        </div>
        @endif

        <!-- Production Stages -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-list mr-2 text-indigo-600"></i>
                Production Stages
            </h3>
            
            @if($productionOrder->stages->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stage</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($productionOrder->stages as $stage)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $stage->stage_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stage->employee ? $stage->employee->user->name : 'Not assigned' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                                        @if($stage->status == 'completed') bg-green-100 text-green-800
                                        @elseif($stage->status == 'in-progress') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('-', ' ', $stage->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $stage->start_time ? $stage->start_time->format('M d, Y H:i') : 'Not started' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $stage->end_time ? $stage->end_time->format('M d, Y H:i') : 'Not completed' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $stage->duration_minutes ? $stage->duration_minutes . ' min' : 'Not calculated' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($stage->status == 'pending')
                                        <form action="{{ route('productions.start-stage', $stage) }}" method="POST" class="inline-block">
                                            @csrf
                                            <div class="flex items-center space-x-2 space-x-reverse">
                                                <select name="employee_id" class="text-sm border border-gray-300 rounded px-2 py-1" required>
                                                    <option value="">Select Employee</option>
                                                    @foreach($employees as $employee)
                                                        <option value="{{ $employee->id }}">{{ $employee->user->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                                    <i class="fas fa-play mr-1"></i>
                                                    Start
                                                </button>
                                            </div>
                                        </form>
                                    @elseif($stage->status == 'in-progress')
                                        <form action="{{ route('productions.complete-stage', $stage) }}" method="POST" class="inline-block" 
                                              onsubmit="return confirm('Are you sure you want to complete this stage?')">
                                            @csrf
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                                <i class="fas fa-check mr-1"></i>
                                                Complete
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-sm">Completed</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-list text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">No production stages found for this order</p>
                    <p class="text-gray-400 text-sm mt-2">Production stages will be created automatically based on the product's BOM</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function productionDetails() {
    return {
        init() {
            // Initialize any functionality needed for production details
        }
    }
}
</script>
@endsection