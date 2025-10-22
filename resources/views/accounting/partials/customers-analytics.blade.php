<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="card">
        <div class="card-body text-center">
            <p class="text-sm mb-1" style="color: #666;">Total Customers</p>
            <p class="text-4xl font-bold" style="color: #1a1a1a;">{{ $data['total_customers'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">New Customers</p>
            <p class="text-4xl font-bold">{{ $data['new_customers'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #d4af37 0%, #c9a84a 100%);">
        <div class="card-body text-center" style="color: #1a1a1a;">
            <p class="text-sm mb-1 opacity-80">Active Customers</p>
            <p class="text-4xl font-bold">{{ $data['active_customers'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Activity Rate</p>
            <p class="text-4xl font-bold">{{ $data['total_customers'] > 0 ? number_format(($data['active_customers'] / $data['total_customers']) * 100, 1) : 0 }}%</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4 class="text-xl font-bold flex items-center" style="color: #1a1a1a;">
            <i class="fas fa-star mr-2" style="color: #d4af37;"></i>
            Top 10 Customers
        </h4>
    </div>
    <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="text-left">
                        <th class="pb-3">Customer</th>
                        <th class="pb-3 text-right">Total Orders Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['top_customers'] as $customer)
                    <tr>
                        <td class="py-3">
                            <p class="font-semibold" style="color: #1a1a1a;">{{ $customer->name }}</p>
                            <p class="text-sm" style="color: #666;">{{ $customer->email }}</p>
                        </td>
                        <td class="py-3 text-right">
                            <p class="font-bold" style="color: #10b981;">{{ number_format($customer->orders_sum_total_amount ?? 0, 3) }} KWD</p>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
