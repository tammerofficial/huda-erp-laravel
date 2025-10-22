<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="card">
        <div class="card-body text-center">
            <p class="text-sm mb-1" style="color: #666;">Purchase Orders</p>
            <p class="text-4xl font-bold" style="color: #1a1a1a;">{{ $data['total_purchase_orders'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Total Spent</p>
            <p class="text-3xl font-bold">{{ number_format($data['total_spent'], 0) }} KWD</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Pending Orders</p>
            <p class="text-4xl font-bold">{{ $data['pending_orders'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Average PO Value</p>
            <p class="text-3xl font-bold">{{ number_format($data['average_po_value'], 0) }} KWD</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4 class="text-xl font-bold flex items-center" style="color: #1a1a1a;">
            <i class="fas fa-trophy mr-2" style="color: #d4af37;"></i>
            Top Suppliers
        </h4>
    </div>
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="text-left">
                        <th class="pb-3">Supplier</th>
                        <th class="pb-3">Email</th>
                        <th class="pb-3 text-right">Total Purchases</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['top_suppliers'] as $supplier)
                    <tr>
                        <td class="py-3">
                            <p class="font-semibold" style="color: #1a1a1a;">{{ $supplier->name }}</p>
                            <p class="text-sm" style="color: #666;">📞 {{ $supplier->phone }}</p>
                        </td>
                        <td class="py-3">
                            <p class="text-sm" style="color: #666;">{{ $supplier->email }}</p>
                        </td>
                        <td class="py-3 text-right">
                            <p class="font-bold text-lg" style="color: #ef4444;">{{ number_format($supplier->purchase_orders_sum_total_amount ?? 0, 3) }} KWD</p>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>