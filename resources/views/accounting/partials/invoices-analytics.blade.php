<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="card">
        <div class="card-body text-center">
            <p class="text-sm mb-1" style="color: #666;">Total Invoices</p>
            <p class="text-4xl font-bold" style="color: #1a1a1a;">{{ $data['total_invoices'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Paid</p>
            <p class="text-4xl font-bold">{{ number_format($data['total_paid'], 0) }} KWD</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Pending</p>
            <p class="text-4xl font-bold">{{ number_format($data['total_pending'], 0) }} KWD</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Overdue</p>
            <p class="text-4xl font-bold">{{ number_format($data['total_overdue'], 0) }} KWD</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card">
        <div class="card-header">
            <h4 class="text-xl font-bold flex items-center" style="color: #1a1a1a;">
                <i class="fas fa-chart-pie mr-2" style="color: #d4af37;"></i>
                Invoices by Status
            </h4>
        </div>
        <div class="card-body">
            <div style="position: relative; height: 250px;">
                <canvas id="invoicesStatusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="text-xl font-bold flex items-center" style="color: #1a1a1a;">
                <i class="fas fa-calculator mr-2" style="color: #d4af37;"></i>
                Invoice Statistics
            </h4>
        </div>
        <div class="card-body">
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 rounded-lg border" style="border-color: #e5e5e5; background: #fafafa;">
                    <span style="color: #1a1a1a;">Average Invoice Value</span>
                    <span class="text-2xl font-bold" style="color: #3b82f6;">{{ number_format($data['average_invoice'], 3) }} KWD</span>
                </div>
                <div class="flex justify-between items-center p-4 rounded-lg border" style="border-color: #e5e5e5; background: #fafafa;">
                    <span style="color: #1a1a1a;">Total Billed Amount</span>
                    <span class="text-2xl font-bold" style="color: #8b5cf6;">{{ number_format($data['total_billed'], 0) }} KWD</span>
                </div>
                <div class="flex justify-between items-center p-4 rounded-lg border" style="border-color: #e5e5e5; background: #fafafa;">
                    <span style="color: #1a1a1a;">Collection Rate</span>
                    <span class="text-2xl font-bold" style="color: #10b981;">{{ $data['total_billed'] > 0 ? number_format(($data['total_paid'] / $data['total_billed']) * 100, 1) : 0 }}%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
if (document.getElementById('invoicesStatusChart')) {
    const ctx = document.getElementById('invoicesStatusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: @json($data['by_status']->pluck('status')),
            datasets: [{
                data: @json($data['by_status']->pluck('total')),
                backgroundColor: [
                    '#10b981',
                    '#f59e0b',
                    '#ef4444',
                    '#3b82f6'
                ],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: true,
                    position: 'bottom',
                    labels: { 
                        color: '#1a1a1a',
                        font: { weight: 'bold' }
                    }
                },
                tooltip: {
                    backgroundColor: '#1a1a1a',
                    titleColor: '#d4af37',
                    bodyColor: '#ffffff',
                    borderColor: '#d4af37',
                    borderWidth: 2,
                    padding: 12
                }
            }
        }
    });
}
</script>