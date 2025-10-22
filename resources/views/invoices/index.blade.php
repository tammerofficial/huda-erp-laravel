@extends('layouts.app')

@section('title', 'Invoices Management')
@section('page-title', 'Invoices Management')

@section('content')
<div x-data="invoiceIndex()">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">📄 Invoices Management</h2>
                <p class="text-gray-600 mt-1">Manage customer invoices and payments efficiently</p>
            </div>
            <a href="{{ route('invoices.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i>
                Create Invoice
            </a>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" 
                       x-model="searchTerm"
                       @input="filterInvoices()"
                       placeholder="Search by invoice number, customer..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select x-model="selectedStatus" 
                        @change="filterInvoices()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="draft">Draft</option>
                    <option value="sent">Sent</option>
                    <option value="paid">Paid</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                <select x-model="selectedPaymentStatus" 
                        @change="filterInvoices()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Payment Status</option>
                    <option value="pending">Pending</option>
                    <option value="partial">Partial</option>
                    <option value="paid">Paid</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center text-white text-sm font-semibold">
                                    #{{ $invoice->id }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $invoice->order->customer->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $invoice->order->order_number ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $invoice->invoice_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $invoice->due_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">KWD {{ number_format($invoice->final_amount, 2) }}</div>
                            <div class="text-xs text-gray-500">Total: {{ number_format($invoice->total_amount, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-800',
                                    'sent' => 'bg-blue-100 text-blue-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    'overdue' => 'bg-red-100 text-red-800'
                                ];
                                $status = $invoice->status ?? 'draft';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $paymentColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'partial' => 'bg-blue-100 text-blue-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    'overdue' => 'bg-red-100 text-red-800'
                                ];
                                $paymentStatus = $invoice->payment_status ?? 'pending';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $paymentColors[$paymentStatus] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($paymentStatus) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('invoices.show', $invoice) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('invoices.edit', $invoice) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($invoice->status == 'draft')
                                    <form action="{{ route('invoices.send', $invoice) }}" method="POST" class="inline" onsubmit="return confirm('Send this invoice?')">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900" title="Send Invoice">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </form>
                                @endif
                                @if($invoice->status == 'sent' && $invoice->payment_status != 'paid')
                                    <form action="{{ route('invoices.mark-paid', $invoice) }}" method="POST" class="inline" onsubmit="return confirm('Mark as paid?')">
                                        @csrf
                                        <button type="submit" class="text-purple-600 hover:text-purple-900" title="Mark Paid">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                @endif
                                <button @click="deleteInvoice({{ $invoice->id }})" 
                                        class="text-red-600 hover:text-red-900" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $invoice->id }}" 
                                      action="{{ route('invoices.destroy', $invoice) }}" 
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
                                <i class="fas fa-file-invoice text-4xl text-gray-300 mb-2"></i>
                                <p>No invoices found</p>
                                <a href="{{ route('invoices.create') }}" 
                                   class="mt-2 text-blue-600 hover:text-blue-800">
                                    Create your first invoice
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($invoices->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function invoiceIndex() {
    return {
        searchTerm: '',
        selectedStatus: '',
        selectedPaymentStatus: '',
        
        filterInvoices() {
            console.log('Filtering invoices...', {
                search: this.searchTerm,
                status: this.selectedStatus,
                paymentStatus: this.selectedPaymentStatus
            });
        },
        
        deleteInvoice(invoiceId) {
            if (confirm('Are you sure you want to delete this invoice?')) {
                document.getElementById(`delete-form-${invoiceId}`).submit();
            }
        }
    }
}
</script>
@endsection
