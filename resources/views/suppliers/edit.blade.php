@extends('layouts.app')

@section('title', 'Edit Supplier')
@section('page-title', 'Edit Supplier')

@section('content')
<div x-data="supplierForm()">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">✏️ Edit Supplier</h2>
                    <p class="text-gray-600 mt-1">Update supplier information</p>
                </div>
                <div class="flex space-x-3 space-x-reverse">
                    <a href="{{ route('suppliers.show', $supplier) }}" 
                       class="btn-secondary px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        View Supplier
                    </a>
                    <a href="{{ route('suppliers.index') }}" 
                       class="btn-secondary px-4 py-2 rounded-lg flex items-center transition-colors">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Back to Suppliers
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('suppliers.update', $supplier) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Company Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-building mr-2 text-blue-600"></i>
                    Company Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Company Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                               value="{{ old('name', $supplier->name) }}"
                               placeholder="Enter company name"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="supplier_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Supplier Type <span class="text-red-500">*</span>
                        </label>
                        <select name="supplier_type" id="supplier_type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('supplier_type') border-red-500 @enderror">
                            <option value="">Select Type</option>
                            <option value="material" {{ old('supplier_type', $supplier->supplier_type) == 'material' ? 'selected' : '' }}>Material Supplier</option>
                            <option value="service" {{ old('supplier_type', $supplier->supplier_type) == 'service' ? 'selected' : '' }}>Service Provider</option>
                            <option value="both" {{ old('supplier_type', $supplier->supplier_type) == 'both' ? 'selected' : '' }}>Both Material & Service</option>
                        </select>
                        @error('supplier_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Person
                        </label>
                        <input type="text" name="contact_person" id="contact_person"
                               value="{{ old('contact_person', $supplier->contact_person) }}"
                               placeholder="Enter contact person name"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('contact_person') border-red-500 @enderror">
                        @error('contact_person')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address
                        </label>
                        <input type="email" name="email" id="email"
                               value="{{ old('email', $supplier->email) }}"
                               placeholder="supplier@example.com"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number
                        </label>
                        <input type="tel" name="phone" id="phone"
                               value="{{ old('phone', $supplier->phone) }}"
                               placeholder="+965 1234 5678"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tax_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Tax Number
                        </label>
                        <input type="text" name="tax_number" id="tax_number"
                               value="{{ old('tax_number', $supplier->tax_number) }}"
                               placeholder="Enter tax number"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('tax_number') border-red-500 @enderror">
                        @error('tax_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-map-marker-alt mr-2 text-green-600"></i>
                    Address Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                            City
                        </label>
                        <input type="text" name="city" id="city"
                               value="{{ old('city', $supplier->city) }}"
                               placeholder="Kuwait City"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('city') border-red-500 @enderror">
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                            Country
                        </label>
                        <input type="text" name="country" id="country"
                               value="{{ old('country', $supplier->country) }}"
                               placeholder="Kuwait"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('country') border-red-500 @enderror">
                        @error('country')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Address
                    </label>
                    <textarea name="address" id="address" rows="3"
                              placeholder="Enter complete address..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address', $supplier->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Business Terms -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-handshake mr-2 text-purple-600"></i>
                    Business Terms
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="credit_limit" class="block text-sm font-medium text-gray-700 mb-2">
                            Credit Limit (KWD)
                        </label>
                        <input type="number" name="credit_limit" id="credit_limit" step="0.01" min="0"
                               value="{{ old('credit_limit', $supplier->credit_limit) }}"
                               placeholder="0.00"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('credit_limit') border-red-500 @enderror">
                        @error('credit_limit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-2">
                            Payment Terms
                        </label>
                        <input type="text" name="payment_terms" id="payment_terms"
                               value="{{ old('payment_terms', $supplier->payment_terms) }}"
                               placeholder="e.g., Net 30, COD, etc."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('payment_terms') border-red-500 @enderror">
                        @error('payment_terms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                              placeholder="Additional notes about this supplier..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes', $supplier->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-gray-900 border-gray-300 rounded">
                        <label for="is_active" class="mr-2 block text-sm text-gray-900">
                            Active Supplier
                        </label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <div class="flex justify-end space-x-4 space-x-reverse">
                    <a href="{{ route('suppliers.index') }}" 
                       class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 btn-primary transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Update Supplier
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function supplierForm() {
    return {
        // Form validation and interaction logic can be added here
        init() {
            // Initialize any form-specific functionality
        }
    }
}
</script>
@endsection
