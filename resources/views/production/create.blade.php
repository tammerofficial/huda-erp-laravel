@extends('layouts.app')

@section('title', 'Add New Production Order')
@section('page-title', 'Add New Production Order')

@section('content')
<div x-data="productionForm()">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">🏭 Add New Production Order</h2>
                    <p class="text-gray-600 mt-1">Create a new production order for manufacturing</p>
                </div>
                <a href="{{ route('productions.index') }}" 
                   class="btn-secondary px-4 py-2 rounded-lg flex items-center transition-colors">
                    <i class="fas fa-arrow-right mr-2"></i>
                    Back to Production Orders
                </a>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div x-show="loading" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-spinner fa-spin text-blue-600 mr-3"></i>
                <span class="text-blue-800">Loading order details...</span>
            </div>
        </div>

        <!-- Customer Info (shown after order selection) -->
        <div x-show="selectedOrderData.customer" class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-sm border border-blue-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user-circle text-blue-600 mr-2"></i>
                        Customer Information
                    </h3>
                    <p class="text-gray-700 mt-2" x-text="'Customer: ' + (selectedOrderData.customer?.name || '')"></p>
                    <p class="text-gray-600 text-sm mt-1" x-text="'Order: ' + (selectedOrderData.order?.order_number || '')"></p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium"
                          :class="{
                              'bg-red-100 text-red-800': selectedOrderData.order?.priority === 'urgent',
                              'bg-orange-100 text-orange-800': selectedOrderData.order?.priority === 'high',
                              'bg-blue-100 text-blue-800': selectedOrderData.order?.priority === 'normal',
                              'bg-gray-100 text-gray-800': selectedOrderData.order?.priority === 'low'
                          }"
                          x-text="selectedOrderData.order?.priority ? selectedOrderData.order.priority.toUpperCase() : ''">
                    </span>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('productions.store') }}" method="POST" id="productionForm" class="space-y-6">
            @csrf
            
            <!-- Hidden Order ID -->
            <input type="hidden" name="order_id" id="order_id" x-model="selectedOrderId">
            
            <!-- Orders Grid -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-shopping-cart mr-2 text-blue-600"></i>
                    Select Order to Start Production
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($orders as $order)
                    <div class="border-2 rounded-lg p-4 cursor-pointer transition-all hover:shadow-md"
                         :class="selectedOrderId == {{ $order->id }} ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300'"
                         @click="selectOrder({{ $order->id }})">
                        
                        <!-- Order Header -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 text-lg">{{ $order->order_number }}</h4>
                                <p class="text-sm text-gray-600 flex items-center mt-1">
                                    <i class="fas fa-user text-gray-400 mr-1"></i>
                                    {{ $order->customer->name }}
                                </p>
                            </div>
                            <div x-show="selectedOrderId == {{ $order->id }}">
                                <i class="fas fa-check-circle text-blue-600 text-2xl"></i>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">
                                    <i class="fas fa-calendar-alt text-gray-400 mr-1"></i>
                                    Order Date:
                                </span>
                                <span class="font-medium text-gray-900">{{ $order->order_date->format('M d, Y') }}</span>
                            </div>
                            
                            @if($order->delivery_date)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">
                                    <i class="fas fa-truck text-gray-400 mr-1"></i>
                                    Delivery:
                                </span>
                                <span class="font-medium text-gray-900">{{ $order->delivery_date->format('M d, Y') }}</span>
                            </div>
                            @endif

                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">
                                    <i class="fas fa-money-bill-wave text-gray-400 mr-1"></i>
                                    Amount:
                                </span>
                                <span class="font-bold text-green-600">{{ number_format($order->final_amount, 2) }} KWD</span>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">
                                    <i class="fas fa-box text-gray-400 mr-1"></i>
                                    Items:
                                </span>
                                <span class="font-medium text-gray-900">{{ $order->orderItems->count() }} Product(s)</span>
                            </div>
                        </div>

                        <!-- Priority Badge -->
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($order->priority === 'urgent') bg-red-100 text-red-800
                                @elseif($order->priority === 'high') bg-orange-100 text-orange-800
                                @elseif($order->priority === 'normal') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                <i class="fas fa-flag mr-1"></i>
                                {{ ucfirst($order->priority ?? 'normal') }}
                            </span>
                            
                            <button type="button"
                                    @click.stop="selectOrder({{ $order->id }})"
                                    :disabled="selectedOrderId == {{ $order->id }}"
                                    class="px-3 py-1 rounded-lg text-sm font-medium transition-colors"
                                    :class="selectedOrderId == {{ $order->id }} 
                                        ? 'bg-blue-600 text-white cursor-default' 
                                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
                                <i class="fas" :class="selectedOrderId == {{ $order->id }} ? 'fa-check' : 'fa-arrow-right'"></i>
                                <span x-text="selectedOrderId == {{ $order->id }} ? 'Selected' : 'Select'"></span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($orders->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-600">No orders available for production</p>
                    <p class="text-sm text-gray-500 mt-2">Orders with "on-hold" status will appear here</p>
                </div>
                @endif

                @error('order_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Products from Order -->
            <div x-show="selectedOrderData.products && selectedOrderData.products.length > 0" 
                 class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-box-open mr-2 text-green-600"></i>
                    Products in Order
                </h3>
                
                <div class="space-y-4">
                    <template x-for="(product, index) in selectedOrderData.products" :key="product.id">
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors cursor-pointer"
                             @click="selectProduct(product)"
                             :class="selectedProduct?.id === product.id ? 'bg-blue-50 border-blue-400' : ''">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <input type="radio" 
                                               :id="'product_' + product.id" 
                                               name="product_id" 
                                               :value="product.id"
                                               :checked="selectedProduct?.id === product.id"
                                               class="mr-3 h-4 w-4 text-blue-600">
                                        <label :for="'product_' + product.id" class="font-medium text-gray-900 cursor-pointer" x-text="product.name"></label>
                                    </div>
                                    <div class="mt-2 ml-7 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-500">Quantity:</span>
                                            <span class="font-medium text-gray-900 ml-1" x-text="product.quantity"></span>
                                        </div>
                                        <div x-show="product.size">
                                            <span class="text-gray-500">Size:</span>
                                            <span class="font-medium text-gray-900 ml-1" x-text="product.size"></span>
                                        </div>
                                        <div x-show="product.color">
                                            <span class="text-gray-500">Color:</span>
                                            <span class="font-medium text-gray-900 ml-1" x-text="product.color"></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Est. Cost:</span>
                                            <span class="font-medium text-gray-900 ml-1" x-text="product.estimated_cost + ' KWD'"></span>
                                        </div>
                                    </div>
                                    <div x-show="product.notes" class="mt-2 ml-7 text-sm text-gray-600">
                                        <i class="fas fa-comment text-gray-400 mr-1"></i>
                                        <span x-text="product.notes"></span>
                                    </div>
                                </div>
                                <div x-show="selectedProduct?.id === product.id">
                                    <i class="fas fa-check-circle text-blue-600 text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Production Details -->
            <div x-show="selectedProduct" class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-cogs mr-2 text-green-600"></i>
                    Production Details
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            Quantity <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="quantity" id="quantity" min="1" required
                               x-model="formData.quantity"
                               placeholder="Enter quantity to produce"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('quantity') border-red-500 @enderror">
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Due Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="due_date" id="due_date" required
                               x-model="formData.due_date"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('due_date') border-red-500 @enderror">
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" id="priority" required
                                x-model="formData.priority"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('priority') border-red-500 @enderror">
                            <option value="">Select Priority</option>
                            <option value="low">Low</option>
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="estimated_cost" class="block text-sm font-medium text-gray-700 mb-2">
                            Estimated Cost (KWD)
                        </label>
                        <input type="number" name="estimated_cost" id="estimated_cost" step="0.01" min="0"
                               x-model="formData.estimated_cost"
                               placeholder="0.00"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('estimated_cost') border-red-500 @enderror">
                        @error('estimated_cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div x-show="selectedProduct" class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-comment mr-2 text-purple-600"></i>
                    Additional Information
                </h3>
                
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes
                    </label>
                    <textarea name="notes" id="notes" rows="4"
                              x-model="formData.notes"
                              placeholder="Enter any production notes or special instructions..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('notes') border-red-500 @enderror"></textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div x-show="selectedProduct" class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex justify-end space-x-4 space-x-reverse">
                    <a href="{{ route('productions.index') }}" 
                       class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 btn-primary transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Create Production Order
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function productionForm() {
    return {
        loading: false,
        selectedOrderId: null,
        selectedOrderData: {
            order: null,
            customer: null,
            products: []
        },
        selectedProduct: null,
        formData: {
            quantity: '',
            due_date: '',
            priority: 'normal',
            estimated_cost: '',
            notes: ''
        },
        
        init() {
            // Check if there's an old order_id value (validation error)
            const oldOrderId = '{{ old("order_id") }}';
            if (oldOrderId) {
                this.selectOrder(parseInt(oldOrderId));
            }
        },
        
        selectOrder(orderId) {
            this.selectedOrderId = orderId;
            this.loadOrderDetails(orderId);
        },
        
        async loadOrderDetails(orderId) {
            if (!orderId) {
                this.resetForm();
                return;
            }
            
            this.loading = true;
            
            try {
                const response = await fetch(`/productions/order/${orderId}/details`);
                const data = await response.json();
                
                this.selectedOrderData = data;
                
                // Auto-fill some form data from order
                this.formData.due_date = data.order.delivery_date || '';
                this.formData.priority = data.order.priority || 'normal';
                this.formData.notes = data.order.notes || '';
                
                // Auto-select first product if only one product
                if (data.products.length === 1) {
                    this.selectProduct(data.products[0]);
                }
                
            } catch (error) {
                console.error('Error loading order details:', error);
                alert('فشل تحميل تفاصيل الطلب');
            } finally {
                this.loading = false;
            }
        },
        
        selectProduct(product) {
            this.selectedProduct = product;
            
            // Auto-fill product data
            this.formData.quantity = product.quantity;
            this.formData.estimated_cost = product.estimated_cost;
            
            // Append product notes to existing notes if any
            if (product.notes && !this.formData.notes.includes(product.notes)) {
                this.formData.notes += (this.formData.notes ? '\n' : '') + 
                    `Product Notes: ${product.notes}`;
            }
            
            // Add size and color info to notes
            const details = [];
            if (product.size) details.push(`Size: ${product.size}`);
            if (product.color) details.push(`Color: ${product.color}`);
            
            if (details.length > 0) {
                const detailsText = details.join(', ');
                if (!this.formData.notes.includes(detailsText)) {
                    this.formData.notes += (this.formData.notes ? '\n' : '') + detailsText;
                }
            }
        },
        
        resetForm() {
            this.selectedOrderId = null;
            this.selectedOrderData = {
                order: null,
                customer: null,
                products: []
            };
            this.selectedProduct = null;
            this.formData = {
                quantity: '',
                due_date: '',
                priority: 'normal',
                estimated_cost: '',
                notes: ''
            };
        }
    }
}
</script>
@endsection