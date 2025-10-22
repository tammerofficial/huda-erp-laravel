<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Sync WooCommerce every 5 minutes, avoid overlapping runs
        $schedule->command('woocommerce:sync')
            ->everyFiveMinutes()
            ->withoutOverlapping()
            ->onOneServer();

        // Check low stock and create auto-purchase orders daily at 9 AM
        $schedule->command('stock:check-low --create-orders')
            ->dailyAt('09:00')
            ->withoutOverlapping()
            ->onOneServer();

        // Send low stock alerts daily at 8 AM (notification only)
        $schedule->command('stock:check-low')
            ->dailyAt('08:00')
            ->withoutOverlapping()
            ->onOneServer();

        // Production workflow alerts
        $schedule->command('alerts:check-low-stock')
            ->hourly()
            ->withoutOverlapping()
            ->onOneServer();

        // Generate monthly payroll on the 25th of each month
        $schedule->command('payroll:generate-monthly')
            ->monthlyOn(25, '09:00')
            ->withoutOverlapping()
            ->onOneServer();

        // Daily production report
        $schedule->command('reports:production-daily')
            ->dailyAt('18:00')
            ->withoutOverlapping()
            ->onOneServer();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}


