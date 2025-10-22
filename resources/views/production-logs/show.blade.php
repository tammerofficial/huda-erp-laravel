@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Production Log #{{ $productionLog->id }}</h1>
            <div class="flex space-x-4">
                <a href="{{ route('production-logs.edit', $productionLog) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Edit
                </a>
                <a href="{{ route('production-logs.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Back to List
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Production Details</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Employee</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                <a href="{{ route('employees.show', $productionLog->employee) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $productionLog->employee->user->name }}
                                </a>
                            </p>
                            <p class="text-sm text-gray-600">{{ $productionLog->employee->employee_id }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Production Stage</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $productionLog->productionStage->stage_name }}</p>
                            <p class="text-sm text-gray-600">
                                <a href="{{ route('productions.show', $productionLog->productionStage->productionOrder) }}" class="text-blue-600 hover:text-blue-800">
                                    Order #{{ $productionLog->productionStage->productionOrder->id }}
                                </a>
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Product</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                <a href="{{ route('products.show', $productionLog->product) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $productionLog->product->name }}
                                </a>
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Pieces Completed</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $productionLog->pieces_completed }} pieces</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Start Time</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $productionLog->start_time->format('Y-m-d H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">End Time</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $productionLog->end_time ? $productionLog->end_time->format('Y-m-d H:i') : 'Not completed' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Performance Metrics</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600">{{ $productionLog->duration_minutes ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-600">Duration (minutes)</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">{{ number_format($productionLog->earnings, 3) }} KWD</div>
                            <div class="text-sm text-gray-600">Earnings</div>
                        </div>
                        
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-600">
                                {{ $productionLog->efficiency_rate ? number_format($productionLog->efficiency_rate, 1) . '%' : 'N/A' }}
                            </div>
                            <div class="text-sm text-gray-600">Efficiency Rate</div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($productionLog->notes)
                <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Notes</h2>
                    <p class="text-gray-900">{{ $productionLog->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Status Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Status</h2>
                    
                    <div class="text-center">
                        @if($productionLog->quality_status === 'approved')
                        <div class="text-6xl text-green-600 mb-4">✅</div>
                        <div class="text-2xl font-bold text-green-600 mb-2">Approved</div>
                        <div class="text-sm text-gray-600">Quality approved</div>
                        @elseif($productionLog->quality_status === 'rejected')
                        <div class="text-6xl text-red-600 mb-4">❌</div>
                        <div class="text-2xl font-bold text-red-600 mb-2">Rejected</div>
                        <div class="text-sm text-gray-600">Quality rejected</div>
                        @else
                        <div class="text-6xl text-yellow-600 mb-4">⏳</div>
                        <div class="text-2xl font-bold text-yellow-600 mb-2">Pending</div>
                        <div class="text-sm text-gray-600">Awaiting quality check</div>
                        @endif
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="text-sm text-gray-600">
                            <div class="flex justify-between mb-2">
                                <span>Created:</span>
                                <span>{{ $productionLog->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Updated:</span>
                                <span>{{ $productionLog->updated_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Production Summary -->
                <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Production Summary</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Rate per piece:</span>
                            <span class="font-semibold text-gray-900">{{ number_format($productionLog->rate_per_piece, 3) }} KWD</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Pieces completed:</span>
                            <span class="font-semibold text-gray-900">{{ $productionLog->pieces_completed }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Expected duration:</span>
                            <span class="font-semibold text-gray-900">{{ $productionLog->expected_duration ?? 'N/A' }} min</span>
                        </div>
                        
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                            <span class="text-gray-600 font-semibold">Total earnings:</span>
                            <span class="font-bold text-lg text-gray-900">{{ number_format($productionLog->earnings, 3) }} KWD</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Actions</h2>
                    
                    <div class="space-y-3">
                        @if($productionLog->quality_status === 'pending')
                        <form action="{{ route('production-logs.approve', $productionLog) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                Approve Quality
                            </button>
                        </form>
                        
                        <form action="{{ route('production-logs.reject', $productionLog) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                Reject Quality
                            </button>
                        </form>
                        @endif
                        
                        @if(!$productionLog->end_time)
                        <form action="{{ route('production-logs.complete', $productionLog) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                Complete Production
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
