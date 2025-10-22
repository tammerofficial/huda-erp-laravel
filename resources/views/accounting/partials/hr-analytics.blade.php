<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="card">
        <div class="card-body text-center">
            <p class="text-sm mb-1" style="color: #666;">Total Employees</p>
            <p class="text-4xl font-bold" style="color: #1a1a1a;">{{ $data['total_employees'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Active Employees</p>
            <p class="text-4xl font-bold">{{ $data['active_employees'] }}</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Total Payroll</p>
            <p class="text-3xl font-bold">{{ number_format($data['total_payroll'], 0) }} KWD</p>
        </div>
    </div>
    <div class="card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
        <div class="card-body text-center text-white">
            <p class="text-sm mb-1 opacity-90">Paid Payroll</p>
            <p class="text-3xl font-bold">{{ number_format($data['paid_payroll'], 0) }} KWD</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card">
        <div class="card-header">
            <h4 class="text-xl font-bold flex items-center" style="color: #1a1a1a;">
                <i class="fas fa-chart-pie mr-2" style="color: #d4af37;"></i>
                Employees by Department
            </h4>
        </div>
        <div class="card-body">
            <div style="position: relative; height: 250px;">
                <canvas id="departmentChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="text-xl font-bold flex items-center" style="color: #1a1a1a;">
                <i class="fas fa-users mr-2" style="color: #d4af37;"></i>
                Department Payroll Details
            </h4>
        </div>
        <div class="card-body">
            <div class="space-y-3">
                @foreach($data['by_department'] as $dept)
                <div class="p-4 rounded-lg border" style="border-color: #e5e5e5; background: #fafafa;">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-semibold" style="color: #1a1a1a;">{{ $dept->department ?: 'Unspecified' }}</span>
                        <span class="font-bold" style="color: #3b82f6;">{{ $dept->count }} employees</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span style="color: #666;">Total Payroll:</span>
                        <span class="font-bold" style="color: #10b981;">{{ number_format($dept->total_salary, 0) }} KWD</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
if (document.getElementById('departmentChart')) {
    const ctx = document.getElementById('departmentChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: @json($data['by_department']->pluck('department')),
            datasets: [{
                data: @json($data['by_department']->pluck('count')),
                backgroundColor: [
                    '#3b82f6',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444',
                    '#a855f7'
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