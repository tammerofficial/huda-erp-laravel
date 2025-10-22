<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AlertService;

class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:check-low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for low stock materials and send alerts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $alertService = app(AlertService::class);
        $lowStockCount = $alertService->checkMaterialShortage();
        
        if ($lowStockCount > 0) {
            $this->info("Found {$lowStockCount} materials with low stock. Alerts sent.");
        } else {
            $this->info("No low stock materials found.");
        }
        
        return 0;
    }
}
