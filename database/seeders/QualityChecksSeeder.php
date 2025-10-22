<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QualityCheck;
use App\Models\ProductionOrder;
use App\Models\Employee;
use Carbon\Carbon;

class QualityChecksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productionOrders = ProductionOrder::with('product')->get();
        $inspectors = Employee::where('employment_status', 'active')
            ->whereJsonContains('skills', 'quality_inspection')
            ->get();
        
        if ($productionOrders->isEmpty() || $inspectors->isEmpty()) {
            $this->command->warn('Missing required data. Please run other seeders first.');
            return;
        }

        // Generate quality checks for the last 30 days
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays($i);
            
            // Skip weekends
            if ($date->dayOfWeek == 5 || $date->dayOfWeek == 6) {
                continue;
            }
            
            // Generate 2-4 quality checks per day
            $checksPerDay = rand(2, 4);
            
            for ($j = 0; $j < $checksPerDay; $j++) {
                $order = $productionOrders->random();
                $inspector = $inspectors->random();
                
                $itemsChecked = rand(5, 50);
                $itemsPassed = rand(0, $itemsChecked);
                $itemsFailed = $itemsChecked - $itemsPassed;
                
                $status = $itemsFailed === 0 ? 'passed' : 'failed';
                
                $defects = [];
                if ($itemsFailed > 0) {
                    $defectTypes = [
                        'Stitching defects',
                        'Measurement errors',
                        'Color inconsistencies',
                        'Fabric flaws',
                        'Button alignment issues'
                    ];
                    
                    $numDefects = rand(1, min(3, $itemsFailed));
                    for ($k = 0; $k < $numDefects; $k++) {
                        $defectType = $defectTypes[array_rand($defectTypes)];
                        $defects[$defectType] = 'Found in ' . rand(1, $itemsFailed) . ' items';
                    }
                }
                
                QualityCheck::create([
                    'production_order_id' => $order->id,
                    'product_id' => $order->product_id,
                    'inspector_id' => $inspector->id,
                    'status' => $status,
                    'items_checked' => $itemsChecked,
                    'items_passed' => $itemsPassed,
                    'items_failed' => $itemsFailed,
                    'defects' => $defects,
                    'inspection_date' => $date->setTime(rand(9, 17), rand(0, 59)),
                    'notes' => $this->generateInspectionNotes($status, $itemsFailed),
                ]);
            }
        }

        $this->command->info('Quality checks seeded successfully!');
    }
    
    private function generateInspectionNotes($status, $itemsFailed)
    {
        if ($status === 'passed') {
            $notes = [
                'All items meet quality standards',
                'Excellent workmanship',
                'No defects found',
                'Ready for shipment'
            ];
            return $notes[array_rand($notes)];
        } else {
            $notes = [
                'Items require rework before approval',
                'Quality standards not met',
                'Defects need to be addressed',
                'Return to production for corrections'
            ];
            return $notes[array_rand($notes)];
        }
    }
}
