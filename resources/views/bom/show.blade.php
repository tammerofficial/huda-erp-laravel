@extends('layouts.app')

@section('title', 'BOM Details')
@section('page-title', 'Bill of Materials Details')

@section('content')
<div>
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-start">
            <div class="flex items-start space-x-4">
                @if($billOfMaterial->product->image_url)
                    <img src="{{ $billOfMaterial->product->image_url }}" alt="{{ $billOfMaterial->product->name }}" class="h-20 w-20 rounded object-cover">
                @else
                    <div class="h-20 w-20 rounded bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-box text-gray-400 text-2xl"></i>
                    </div>
                @endif
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $billOfMaterial->product->name }}</h2>
                    <p class="text-gray-600 mt-1">Version: {{ $billOfMaterial->version }}</p>
                    <div class="flex items-center space-x-3 mt-2">
                        @if($billOfMaterial->status == 'active')
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                                Active
                            </span>
                        @elseif($billOfMaterial->status == 'draft')
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full">
                                Draft
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full">
                                Inactive
                            </span>
                        @endif
                        
                        @if($billOfMaterial->is_default)
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-semibold rounded-full">
                                Default BOM
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex space-x-3">
                <a href="{{ route('bom.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>
                <a href="{{ route('bom.edit', $billOfMaterial) }}" class="btn-primary">
                    <i class="fas fa-edit"></i>
                    Edit
                </a>
            </div>
        </div>

        @if($billOfMaterial->description)
        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
            <p class="text-gray-700">{{ $billOfMaterial->description }}</p>
        </div>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Materials</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $billOfMaterial->bomItems->count() }}</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-cubes text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Cost</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($billOfMaterial->total_cost ?? 0, 3) }}</p>
                    <p class="text-xs text-gray-500 mt-1">KWD</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Created By</p>
                    <p class="text-lg font-semibold text-gray-900 mt-2">{{ $billOfMaterial->createdBy->name ?? 'System' }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $billOfMaterial->created_at->format('M d, Y') }}</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-user text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Product Price</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($billOfMaterial->product->price ?? 0, 3) }}</p>
                    <p class="text-xs text-gray-500 mt-1">KWD</p>
                </div>
                <div class="h-12 w-12 rounded-full bg-yellow-100 flex items-center justify-center">
                    <i class="fas fa-tag text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Materials List -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900">Material Requirements</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Cost</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Cost</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($billOfMaterial->bomItems as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $item->material->name }}</div>
                            <div class="text-sm text-gray-500">{{ $item->material->sku }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                {{ ucfirst($item->material->category ?? 'N/A') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                            {{ number_format($item->quantity, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->unit }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            KWD {{ number_format($item->unit_cost, 3) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            KWD {{ number_format($item->total_cost, 3) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-left font-bold text-gray-900">Total Cost:</td>
                        <td class="px-6 py-4 text-left font-bold text-gray-900 text-lg">
                            KWD {{ number_format($billOfMaterial->total_cost ?? 0, 3) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Actions Section -->
    <div class="mt-6 flex justify-between items-center">
        <div class="flex space-x-3">
            @if($billOfMaterial->status != 'active')
            <form action="{{ route('bom.activate', $billOfMaterial) }}" method="POST">
                @csrf
                <button type="submit" class="btn-success">
                    <i class="fas fa-check-circle"></i>
                    Activate BOM
                </button>
            </form>
            @endif

            <form action="{{ route('bom.duplicate', $billOfMaterial) }}" method="POST">
                @csrf
                <button type="submit" class="btn-secondary">
                    <i class="fas fa-copy"></i>
                    Duplicate
                </button>
            </form>
        </div>

        <form action="{{ route('bom.destroy', $billOfMaterial) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this BOM?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-danger">
                <i class="fas fa-trash"></i>
                Delete BOM
            </button>
        </form>
    </div>
</div>
@endsection

