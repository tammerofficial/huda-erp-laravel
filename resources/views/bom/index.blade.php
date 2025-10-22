@extends('layouts.app')

@section('title', 'Bill of Materials (BOM)')
@section('page-title', 'Bill of Materials Management')

@push('head')
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
@endpush

@section('content')
<div x-data="bomIndex()">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">📋 Bill of Materials (BOM)</h2>
                <p class="text-gray-600 mt-1">Manage product recipes and material requirements</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('bom.create') }}" class="btn-primary">
                    <i class="fas fa-plus"></i>
                    Create New BOM
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <form method="GET" action="{{ route('bom.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" 
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Product name, SKU, or version..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                <select name="product_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-search"></i>
                    Search
                </button>
                <a href="{{ route('bom.index') }}" class="btn-secondary" onclick="location.reload(true); return false;">
                    <i class="fas fa-sync-alt"></i>
                    Refresh
                </a>
                <a href="{{ route('bom.index') }}" class="btn-secondary">
                    <i class="fas fa-times"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- BOMs Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Product
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Version
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Materials
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Cost
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Created
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($boms as $bom)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($bom->product->image_url)
                                    <img src="{{ $bom->product->image_url }}" alt="{{ $bom->product->name }}" class="h-10 w-10 rounded object-cover">
                                @else
                                    <div class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-box text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $bom->product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $bom->product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $bom->version }}</div>
                            @if($bom->is_default)
                                <span class="inline-flex px-2 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Default
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $bom->bomItems->count() }} items
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">KWD {{ number_format($bom->total_cost ?? 0, 3) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($bom->status == 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @elseif($bom->status == 'draft')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Draft
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $bom->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('bom.show', $bom) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('bom.edit', $bom) }}" class="text-yellow-600 hover:text-yellow-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($bom->status != 'active')
                                <form action="{{ route('bom.activate', $bom) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900" title="Activate">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('bom.duplicate', $bom) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-purple-600 hover:text-purple-900" title="Duplicate">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </form>
                            <button @click="deleteBOM({{ $bom->id }})" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-clipboard-list text-gray-400 text-5xl mb-4"></i>
                                <p class="text-gray-500 text-lg mb-2">No BOMs found</p>
                                <p class="text-gray-400 text-sm mb-4">Create your first Bill of Materials to get started</p>
                                <a href="{{ route('bom.create') }}" class="btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Create BOM
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($boms->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $boms->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function bomIndex() {
    return {
        deleteBOM(bomId) {
            if (confirm('Are you sure you want to delete this BOM? This action cannot be undone.')) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = `/bom/${bomId}`;
                
                let csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                
                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    }
}
</script>
@endsection

