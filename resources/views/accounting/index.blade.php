@extends('layouts.app')

@section('title', 'Accounting Entries')
@section('page-title', 'Accounting Entries')

@section('content')
<div x-data="accountingIndex()">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">📊 Accounting Entries</h2>
                <p class="text-gray-600 mt-1">Manage revenue, expenses, and financial transactions</p>
            </div>
            <a href="{{ route('accounting.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i>
                New Entry
            </a>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" 
                       x-model="searchTerm"
                       @input="filterEntries()"
                       placeholder="Search by description, reference..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select x-model="selectedType" 
                        @change="filterEntries()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Types</option>
                    <option value="revenue">Revenue</option>
                    <option value="expense">Expense</option>
                    <option value="asset">Asset</option>
                    <option value="liability">Liability</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <input type="text" 
                       x-model="selectedCategory"
                       @input="filterEntries()"
                       placeholder="Filter by category..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                <input type="date" 
                       x-model="dateFrom"
                       @change="filterEntries()"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>
    </div>

    <!-- Accounting Entries Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($accountings as $accounting)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $accounting->date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $typeColors = [
                                    'revenue' => 'bg-green-100 text-green-800',
                                    'expense' => 'bg-red-100 text-red-800',
                                    'asset' => 'bg-blue-100 text-blue-800',
                                    'liability' => 'bg-yellow-100 text-yellow-800'
                                ];
                                $type = $accounting->type ?? 'expense';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $typeColors[$type] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $accounting->category ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ Str::limit($accounting->description ?? '', 60) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold {{ $accounting->type == 'revenue' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $accounting->type == 'revenue' ? '+' : '-' }} KWD {{ number_format($accounting->amount, 2) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($accounting->reference_type && $accounting->reference_id)
                                {{ ucfirst($accounting->reference_type) }} #{{ $accounting->reference_id }}
                            @else
                                <span class="text-gray-400">Manual</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $accounting->createdBy ? $accounting->createdBy->name : 'System' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('accounting.show', $accounting) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('accounting.edit', $accounting) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button @click="deleteEntry({{ $accounting->id }})" 
                                        class="text-red-600 hover:text-red-900" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $accounting->id }}" 
                                      action="{{ route('accounting.destroy', $accounting) }}" 
                                      method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-calculator text-4xl text-gray-300 mb-2"></i>
                                <p>No accounting entries found</p>
                                <a href="{{ route('accounting.create') }}" 
                                   class="mt-2 text-blue-600 hover:text-blue-800">
                                    Create your first entry
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($accountings->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $accountings->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function accountingIndex() {
    return {
        searchTerm: '',
        selectedType: '',
        selectedCategory: '',
        dateFrom: '',
        
        filterEntries() {
            console.log('Filtering entries...', {
                search: this.searchTerm,
                type: this.selectedType,
                category: this.selectedCategory,
                dateFrom: this.dateFrom
            });
        },
        
        deleteEntry(entryId) {
            if (confirm('Are you sure you want to delete this accounting entry?')) {
                document.getElementById(`delete-form-${entryId}`).submit();
            }
        }
    }
}
</script>
@endsection
