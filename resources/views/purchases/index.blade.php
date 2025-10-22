@extends('layouts.app')

@section('title', 'Purchase Orders Management')
@section('page-title', 'Purchase Orders Management')

@section('content')
<div x-data="purchaseIndex()">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">🛒 Purchase Orders Management</h2>
                <p class="text-gray-600 mt-1">Manage supplier purchase orders and inventory procurement</p>
            </div>
            <a href="{{ route('purchases.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i>
                New Purchase Order
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
                       @input="filterPurchases()"
                       placeholder="Search by order number, supplier..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select x-model="selectedStatus" 
                        @change="filterPurchases()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="draft">Draft</option>
                    <option value="sent">Sent</option>
                    <option value="received">Received</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                <select x-model="selectedPaymentStatus" 
                        @change="filterPurchases()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Payment Status</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="partial">Partial</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                <select x-model="selectedPriority" 
                        @change="filterPurchases()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Priorities</option>
                    <option value="urgent">Urgent</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Purchase Orders Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($purchaseOrders as $purchaseOrder)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-orange-500 flex items-center justify-center text-white text-sm font-semibold">
                                    #{{ $purchaseOrder->id }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $purchaseOrder->order_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $purchaseOrder->supplier->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $purchaseOrder->order_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $purchaseOrder->delivery_date ? $purchaseOrder->delivery_date->format('M d, Y') : 'Not Set' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">KWD {{ number_format($purchaseOrder->final_amount, 2) }}</div>
                            <div class="text-xs text-gray-500">Total: {{ number_format($purchaseOrder->total_amount, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-800',
                                    'sent' => 'bg-blue-100 text-blue-800',
                                    'received' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800'
                                ];
                                $status = $purchaseOrder->status ?? 'draft';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $paymentColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    'partial' => 'bg-blue-100 text-blue-800'
                                ];
                                $paymentStatus = $purchaseOrder->payment_status ?? 'pending';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $paymentColors[$paymentStatus] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($paymentStatus) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $priorityColors = [
                                    'urgent' => 'bg-red-100 text-red-800',
                                    'high' => 'bg-orange-100 text-orange-800',
                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                    'low' => 'bg-green-100 text-green-800'
                                ];
                                $priority = $purchaseOrder->priority ?? 'medium';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $priorityColors[$priority] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('purchases.show', $purchaseOrder) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('purchases.edit', $purchaseOrder) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($purchaseOrder->status == 'sent')
                                    <form action="{{ route('purchases.receive', $purchaseOrder) }}" method="POST" class="inline" onsubmit="return confirm('Mark as received?')">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900" title="Mark Received">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                @endif
                                <button @click="deletePurchase({{ $purchaseOrder->id }})" 
                                        class="text-red-600 hover:text-red-900" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $purchaseOrder->id }}" 
                                      action="{{ route('purchases.destroy', $purchaseOrder) }}" 
                                      method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-2"></i>
                                <p>No purchase orders found</p>
                                <a href="{{ route('purchases.create') }}" 
                                   class="mt-2 text-blue-600 hover:text-blue-800">
                                    Create your first purchase order
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($purchaseOrders->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $purchaseOrders->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function purchaseIndex() {
    return {
        searchTerm: '',
        selectedStatus: '',
        selectedPaymentStatus: '',
        selectedPriority: '',
        
        filterPurchases() {
            console.log('Filtering purchases...', {
                search: this.searchTerm,
                status: this.selectedStatus,
                paymentStatus: this.selectedPaymentStatus,
                priority: this.selectedPriority
            });
        },
        
        deletePurchase(purchaseId) {
            if (confirm('Are you sure you want to delete this purchase order?')) {
                document.getElementById(`delete-form-${purchaseId}`).submit();
            }
        }
    }
}
</script>
@endsection
