<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PayrollService;

class GenerateMonthlyPayroll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payroll:generate-monthly {month?} {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly payroll for all employees';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $month = $this->argument('month') ?? now()->month;
        $year = $this->argument('year') ?? now()->year;
        
        $this->info("Generating payroll for {$month}/{$year}...");
        
        $payrollService = app(PayrollService::class);
        $result = $payrollService->generateBulkPayrolls($month, $year, 1); // Admin user ID
        
        $this->info("Generated {$result['generated']->count()} payroll records.");
        
        if (!empty($result['errors'])) {
            $this->error("Errors occurred:");
            foreach ($result['errors'] as $error) {
                $this->error("- {$error['employee']}: {$error['error']}");
            }
        }
        
        return 0;
    }
}
