@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">📝 Production Log Details</h1>
        <div class="flex gap-4">
            <a href="{{ route('production-logs.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                Back to Production Logs
            </a>
            <a href="{{ route('production-logs.edit', $productionLog) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Production Log Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Employee</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $productionLog->employee->user->name ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-600">{{ $productionLog->employee->position ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Production Stage</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $productionLog->productionStage->stage_name ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-600">Order #{{ $productionLog->productionStage->productionOrder->id ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Product</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $productionLog->product->name ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-600">SKU: {{ $productionLog->product->sku ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Quality Status</label>
                        <div class="mt-1">
                            @if($productionLog->quality_status === 'approved')
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    ✅ Approved
                                </span>
                            @elseif($productionLog->quality_status === 'rejected')
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    ❌ Rejected
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    ⏳ Pending
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Start Time</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $productionLog->start_time->format('M d, Y H:i') }}
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500">End Time</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $productionLog->end_time ? $productionLog->end_time->format('M d, Y H:i') : 'Not completed' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Production Metrics -->
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-xl font-semibold mb-4">Production Metrics</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $productionLog->pieces_completed }}</div>
                        <div class="text-sm text-gray-500">Pieces Completed</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $productionLog->rate_per_piece ?? 0 }} KWD</div>
                        <div class="text-sm text-gray-500">Rate Per Piece</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">{{ $productionLog->earnings ?? 0 }} KWD</div>
                        <div class="text-sm text-gray-500">Total Earnings</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-600">{{ $productionLog->duration_minutes ?? 0 }} min</div>
                        <div class="text-sm text-gray-500">Duration</div>
                    </div>
                </div>
            </div>

            <!-- Efficiency Analysis -->
            @if($productionLog->expected_duration && $productionLog->duration_minutes)
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-xl font-semibold mb-4">Efficiency Analysis</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $productionLog->expected_duration }} min</div>
                        <div class="text-sm text-gray-500">Expected Duration</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $productionLog->duration_minutes }} min</div>
                        <div class="text-sm text-gray-500">Actual Duration</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold {{ $productionLog->efficiency_rate > 100 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $productionLog->efficiency_rate ?? 0 }}%
                        </div>
                        <div class="text-sm text-gray-500">Efficiency Rate</div>
                    </div>
                </div>
                
                @if($productionLog->efficiency_rate > 100)
                <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-green-800 font-semibold">Excellent performance! Completed faster than expected.</span>
                    </div>
                </div>
                @elseif($productionLog->efficiency_rate < 80)
                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <span class="text-red-800 font-semibold">Performance below expectations. Consider training or support.</span>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Notes -->
            @if($productionLog->notes)
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-xl font-semibold mb-4">Notes</h2>
                <p class="text-gray-700">{{ $productionLog->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('production-logs.edit', $productionLog) }}" 
                       class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-center block">
                        Edit Production Log
                    </a>
                    
                    @if($productionLog->quality_status === 'pending')
                    <form action="{{ route('production-logs.approve', $productionLog) }}" method="POST" class="inline w-full">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            Approve Quality
                        </button>
                    </form>
                    
                    <form action="{{ route('production-logs.reject', $productionLog) }}" method="POST" class="inline w-full">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                            Reject Quality
                        </button>
                    </form>
                    @endif
                    
                    <form action="{{ route('production-logs.destroy', $productionLog) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this production log?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                            Delete Production Log
                        </button>
                    </form>
                </div>
            </div>

            <!-- Employee Info -->
            @if($productionLog->employee)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Employee</h3>
                <div class="space-y-2">
                    <div>
                        <span class="text-sm text-gray-500">Name:</span>
                        <span class="font-semibold">{{ $productionLog->employee->user->name }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Position:</span>
                        <span class="font-semibold">{{ $productionLog->employee->position }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Department:</span>
                        <span class="font-semibold">{{ $productionLog->employee->department }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Efficiency Rating:</span>
                        <span class="font-semibold">{{ $productionLog->employee->efficiency_rating ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Production Stage Info -->
            @if($productionLog->productionStage)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Production Stage</h3>
                <div class="space-y-2">
                    <div>
                        <span class="text-sm text-gray-500">Stage:</span>
                        <span class="font-semibold">{{ $productionLog->productionStage->stage_name }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Status:</span>
                        <span class="font-semibold">{{ ucfirst($productionLog->productionStage->status) }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Sequence:</span>
                        <span class="font-semibold">{{ $productionLog->productionStage->sequence_order }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection