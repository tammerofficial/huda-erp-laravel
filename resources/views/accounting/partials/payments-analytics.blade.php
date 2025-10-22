<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <!-- Payment Gateway Analytics -->
    <div class="card" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
        <div class="card-header text-white">
            <h4 class="text-xl font-bold flex items-center">
                <i class="fas fa-credit-card mr-2"></i>
                Payment Gateway Analytics
            </h4>
        </div>
        <div class="card-body text-white">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="rounded-lg p-4" style="background: rgba(255,255,255,0.1);">
                    <p class="text-sm opacity-90 mb-1">Total Transactions</p>
                    <p class="text-3xl font-bold">{{ $data['total_transactions'] }}</p>
                </div>
                <div class="rounded-lg p-4" style="background: rgba(255,255,255,0.1);">
                    <p class="text-sm opacity-90 mb-1">Total Amount</p>
                    <p class="text-2xl font-bold">{{ number_format($data['total_amount'], 0) }} KWD</p>
                </div>
                <div class="rounded-lg p-4" style="background: rgba(255,255,255,0.1);">
                    <p class="text-sm opacity-90 mb-1">Fees</p>
                    <p class="text-2xl font-bold">{{ number_format($data['total_fees'], 3) }} KWD</p>
                </div>
                <div class="rounded-lg p-4" style="background: rgba(255,255,255,0.1);">
                    <p class="text-sm opacity-90 mb-1">Net Amount</p>
                    <p class="text-2xl font-bold">{{ number_format($data['net_amount'], 0) }} KWD</p>
                </div>
            </div>

            @if($data['by_gateway']->count() > 0)
            <div class="space-y-2">
                <h5 class="font-semibold mb-2">By Gateway:</h5>
                @foreach($data['by_gateway'] as $gateway)
                <div class="p-3 rounded-lg" style="background: rgba(255,255,255,0.05);">
                    <div class="flex justify-between items-center mb-1">
                        <span class="font-semibold">{{ $gateway->name }}</span>
                        <span class="text-sm opacity-80">{{ $gateway->count }} transactions</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span style="color: #10b981;">Amount: {{ number_format($gateway->total, 0) }} KWD</span>
                        <span style="color: #ef4444;">Fees: {{ number_format($gateway->fees, 3) }} KWD</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 opacity-60">
                <i class="fas fa-credit-card text-4xl mb-3"></i>
                <p>No transactions recorded</p>
            </div>
            @endif
        </div>
    </div>

    <!-- WooCommerce Integration -->
    <div class="card" style="background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);">
        <div class="card-header text-white">
            <h4 class="text-xl font-bold flex items-center">
                <i class="fab fa-woocommerce mr-2"></i>
                WooCommerce Sales
            </h4>
        </div>
        <div class="card-body text-white">
            @if(isset($wooData['total_orders']) && $wooData['total_orders'] > 0)
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="rounded-lg p-4" style="background: rgba(255,255,255,0.1);">
                    <p class="text-sm opacity-90 mb-1">Total Orders</p>
                    <p class="text-3xl font-bold">{{ $wooData['total_orders'] }}</p>
                </div>
                <div class="rounded-lg p-4" style="background: rgba(255,255,255,0.1);">
                    <p class="text-sm opacity-90 mb-1">Total Sales</p>
                    <p class="text-2xl font-bold">{{ number_format($wooData['total_sales'], 0) }} KWD</p>
                </div>
                <div class="rounded-lg p-4" style="background: rgba(255,255,255,0.1);">
                    <p class="text-sm opacity-90 mb-1">Net Profit</p>
                    <p class="text-2xl font-bold">{{ number_format($wooData['total_profit'], 0) }} KWD</p>
                </div>
                <div class="rounded-lg p-4" style="background: rgba(255,255,255,0.1);">
                    <p class="text-sm opacity-90 mb-1">Average Order</p>
                    <p class="text-2xl font-bold">{{ number_format($wooData['average_order'], 0) }} KWD</p>
                </div>
            </div>

            @if($wooData['by_payment_method']->count() > 0)
            <div class="space-y-2">
                <h5 class="font-semibold mb-2">Payment Methods:</h5>
                @foreach($wooData['by_payment_method'] as $method)
                <div class="flex justify-between items-center p-3 rounded-lg" style="background: rgba(255,255,255,0.05);">
                    <div>
                        <span class="font-semibold">{{ $method->payment_method ?: 'Unspecified' }}</span>
                        <p class="text-xs opacity-60">{{ $method->count }} orders</p>
                    </div>
                    <span class="font-bold" style="color: #10b981;">{{ number_format($method->total, 0) }} KWD</span>
                </div>
                @endforeach
            </div>
            @endif

            @else
            <div class="text-center py-12 opacity-60">
                <i class="fab fa-woocommerce text-6xl mb-4"></i>
                <p class="text-lg mb-2">No WooCommerce Data</p>
                <p class="text-sm">Sync sales from WooCommerce store</p>
                <a href="{{ route('woocommerce.sync') }}" class="inline-block mt-4 px-4 py-2 rounded-lg text-white transition-all" style="background: rgba(255,255,255,0.2);">
                    <i class="fas fa-sync mr-2"></i>Sync Now
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Payment Gateway Types -->
<div class="card">
    <div class="card-header">
        <h4 class="text-xl font-bold flex items-center" style="color: #1a1a1a;">
            <i class="fas fa-money-check-alt mr-2" style="color: #d4af37;"></i>
            Available Payment Gateways
        </h4>
    </div>
    <div class="card-body">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="rounded-lg p-4 text-center border" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-color: #3b82f6;">
                <i class="fas fa-university text-4xl text-white mb-3"></i>
                <p class="text-white font-bold text-lg">KNET</p>
                <p class="text-sm text-blue-100">Local Cards</p>
            </div>
            <div class="rounded-lg p-4 text-center border" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-color: #8b5cf6;">
                <i class="fab fa-cc-visa text-4xl text-white mb-3"></i>
                <p class="text-white font-bold text-lg">Visa</p>
                <p class="text-sm text-purple-100">International Cards</p>
            </div>
            <div class="rounded-lg p-4 text-center border" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-color: #f59e0b;">
                <i class="fab fa-cc-mastercard text-4xl text-white mb-3"></i>
                <p class="text-white font-bold text-lg">MasterCard</p>
                <p class="text-sm text-orange-100">International Cards</p>
            </div>
            <div class="rounded-lg p-4 text-center border" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-color: #10b981;">
                <i class="fas fa-money-bill-wave text-4xl text-white mb-3"></i>
                <p class="text-white font-bold text-lg">Cash</p>
                <p class="text-sm text-green-100">Cash on Delivery</p>
            </div>
        </div>
    </div>
</div>