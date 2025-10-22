<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductionLog;
use App\Models\ProductionOrder;
use App\Models\AttendanceRecord;
use App\Services\PDFExportService;
use Carbon\Carbon;

class DailyProductionReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:production-daily {--date=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily production report';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::yesterday();
        
        $this->info("Generating daily production report for {$date->format('Y-m-d')}...");
        
        // Get production data for the day
        $productionLogs = ProductionLog::whereDate('start_time', $date)
            ->with(['employee.user', 'product', 'productionStage'])
            ->get();
        
        $attendanceRecords = AttendanceRecord::whereDate('date', $date)
            ->with('employee.user')
            ->get();
        
        $productionOrders = ProductionOrder::whereDate('created_at', $date)
            ->with('product')
            ->get();
        
        // Calculate statistics
        $stats = [
            'total_employees' => $attendanceRecords->count(),
            'present_employees' => $attendanceRecords->where('status', 'present')->count(),
            'total_production_logs' => $productionLogs->count(),
            'total_pieces_produced' => $productionLogs->sum('pieces_completed'),
            'total_earnings' => $productionLogs->sum('earnings'),
            'avg_efficiency' => $productionLogs->avg('efficiency_rate'),
            'new_orders' => $productionOrders->count(),
        ];
        
        // Generate report content
        $this->info("=== Daily Production Report - {$date->format('Y-m-d')} ===");
        $this->info("Total Employees: {$stats['total_employees']}");
        $this->info("Present Employees: {$stats['present_employees']}");
        $this->info("Production Logs: {$stats['total_production_logs']}");
        $this->info("Pieces Produced: {$stats['total_pieces_produced']}");
        $this->info("Total Earnings: " . number_format($stats['total_earnings'], 3) . " KWD");
        $this->info("Average Efficiency: " . number_format($stats['avg_efficiency'], 1) . "%");
        $this->info("New Orders: {$stats['new_orders']}");
        
        // Top performers
        $topPerformers = $productionLogs->groupBy('employee_id')
            ->map(function ($logs) {
                return [
                    'employee' => $logs->first()->employee->user->name,
                    'pieces' => $logs->sum('pieces_completed'),
                    'earnings' => $logs->sum('earnings'),
                    'efficiency' => $logs->avg('efficiency_rate')
                ];
            })
            ->sortByDesc('pieces')
            ->take(5);
        
        $this->info("\n=== Top 5 Performers ===");
        foreach ($topPerformers as $performer) {
            $this->info("{$performer['employee']}: {$performer['pieces']} pieces, " . 
                      number_format($performer['earnings'], 3) . " KWD, " . 
                      number_format($performer['efficiency'], 1) . "% efficiency");
        }
        
        // Quality issues
        $qualityIssues = $productionLogs->where('quality_status', 'rejected');
        if ($qualityIssues->count() > 0) {
            $this->warn("\n=== Quality Issues ===");
            $this->warn("Rejected production logs: {$qualityIssues->count()}");
            foreach ($qualityIssues as $issue) {
                $this->warn("- {$issue->employee->user->name}: {$issue->pieces_completed} pieces rejected");
            }
        }
        
        $this->info("\nDaily production report completed successfully!");
        
        return 0;
    }
}
