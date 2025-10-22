<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductionLog;
use App\Models\Employee;
use App\Models\ProductionStage;
use App\Models\Product;
use Carbon\Carbon;

class ProductionLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::where('employment_status', 'active')->get();
        $stages = ProductionStage::with('productionOrder')->get();
        $products = Product::all();
        
        if ($employees->isEmpty() || $stages->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Missing required data. Please run other seeders first.');
            return;
        }

        // Generate production logs for the last 30 days
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays($i);
            
            // Skip weekends
            if ($date->dayOfWeek == 5 || $date->dayOfWeek == 6) {
                continue;
            }
            
            // Generate 3-5 production logs per day
            $logsPerDay = rand(3, 5);
            
            for ($j = 0; $j < $logsPerDay; $j++) {
                $employee = $employees->random();
                $stage = $stages->random();
                $product = $products->random();
                
                $startTime = $date->copy()->setTime(8, rand(0, 59));
                $endTime = $startTime->copy()->addMinutes(rand(60, 480)); // 1-8 hours
                
                $piecesCompleted = rand(1, 20);
                $ratePerPiece = rand(50, 200) / 100; // 0.5-2.0 KWD
                $earnings = $piecesCompleted * $ratePerPiece;
                
                $expectedDuration = rand(120, 360); // 2-6 hours in minutes
                $actualDuration = $startTime->diffInMinutes($endTime);
                $efficiencyRate = ($expectedDuration / $actualDuration) * 100;
                
                $qualityStatuses = ['pending', 'approved', 'rejected'];
                $qualityStatus = $qualityStatuses[array_rand($qualityStatuses)];
                
                ProductionLog::create([
                    'employee_id' => $employee->id,
                    'production_stage_id' => $stage->id,
                    'product_id' => $product->id,
                    'pieces_completed' => $piecesCompleted,
                    'rate_per_piece' => $ratePerPiece,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'expected_duration' => $expectedDuration,
                    'efficiency_rate' => $efficiencyRate,
                    'quality_status' => $qualityStatus,
                    'notes' => $this->generateNotes($qualityStatus, $efficiencyRate),
                ]);
            }
        }

        $this->command->info('Production logs seeded successfully!');
    }
    
    private function generateNotes($qualityStatus, $efficiencyRate)
    {
        $notes = [];
        
        if ($qualityStatus === 'rejected') {
            $notes[] = 'Quality issues found: stitching defects';
        }
        
        if ($efficiencyRate > 120) {
            $notes[] = 'Excellent performance, completed ahead of schedule';
        } elseif ($efficiencyRate < 80) {
            $notes[] = 'Performance below expectations, needs improvement';
        }
        
        if ($qualityStatus === 'approved' && $efficiencyRate > 100) {
            $notes[] = 'High quality work completed efficiently';
        }
        
        return empty($notes) ? null : implode('. ', $notes);
    }
}
