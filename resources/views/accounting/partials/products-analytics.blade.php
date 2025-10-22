<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="card">
        <div class="card-body text-center">
            <p class="text-sm mb-1" style="color: #666;">Total Products</p>
            <p class="text-4xl font-bold" style="color: #1a1a1a;">{{ $data['total_products'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Active Products</p>
            <p class="text-4xl font-bold">{{ $data['active_products'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Low Stock</p>
            <p class="text-4xl font-bold">{{ $data['low_stock'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Out of Stock</p>
            <p class="text-4xl font-bold">{{ $data['out_of_stock'] }}</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4 class="text-xl font-bold flex items-center" style="color: #1a1a1a;">
            <i class="fas fa-fire mr-2" style="color: #d4af37;"></i>
            Top 10 Best Selling Products
        </h4>
    </div>
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="text-left">
                        <th class="pb-3">Product</th>
                        <th class="pb-3 text-center">Quantity Sold</th>
                        <th class="pb-3 text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['top_selling'] as $product)
                    <tr>
                        <td class="py-3">
                            <p class="font-semibold" style="color: #1a1a1a;">{{ $product->name }}</p>
                            <p class="text-sm" style="color: #666;">{{ $product->sku }}</p>
                        </td>
                        <td class="py-3 text-center">
                            <span class="badge badge-info">{{ $product->total_quantity }}</span>
                        </td>
                        <td class="py-3 text-right">
                            <p class="font-bold" style="color: #10b981;">{{ number_format($product->total_revenue, 3) }} KWD</p>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
