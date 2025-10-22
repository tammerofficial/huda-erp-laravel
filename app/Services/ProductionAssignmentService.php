<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\ProductionStage;
use App\Models\ProductionOrder;

class ProductionAssignmentService
{
    /**
     * Find best employee for a stage
     */
    public function findBestEmployee($stageName, $productId = null)
    {
        return Employee::where('employment_status', 'active')
            ->where('qr_enabled', true)
            ->whereJsonContains('skills', $stageName)
            ->orderByRaw('current_workload ASC')
            ->orderByRaw('efficiency_rating DESC')
            ->first();
    }

    /**
     * Assign stage to employee
     */
    public function assignStage(ProductionStage $stage, Employee $employee = null)
    {
        if (!$employee) {
            $employee = $this->findBestEmployee(
                $stage->stage_name,
                $stage->productionOrder->product_id
            );
        }

        if (!$employee) {
            throw new \Exception('No suitable employee found for stage: ' . $stage->stage_name);
        }

        $stage->employee_id = $employee->id;
        $stage->status = 'assigned';
        $stage->save();

        // Increase workload
        $employee->increment('current_workload');

        return $stage;
    }

    /**
     * Complete stage
     */
    public function completeStage(ProductionStage $stage)
    {
        $stage->status = 'completed';
        $stage->end_time = now();
        $stage->save();

        // Decrease workload
        if ($stage->employee) {
            $stage->employee->decrement('current_workload');
        }

        return $stage;
    }

    /**
     * Auto-assign all pending stages for a production order
     */
    public function autoAssignProductionOrder(ProductionOrder $productionOrder)
    {
        $stages = $productionOrder->stages()
            ->where('status', 'pending')
            ->orderBy('sequence_order')
            ->get();

        $assignedStages = [];

        foreach ($stages as $stage) {
            try {
                $this->assignStage($stage);
                $assignedStages[] = $stage;
            } catch (\Exception $e) {
                // Log error but continue with other stages
                \Log::error('Failed to assign stage: ' . $e->getMessage());
            }
        }

        return $assignedStages;
    }

    /**
     * Get available employees for a stage
     */
    public function getAvailableEmployees($stageName, $limit = 5)
    {
        return Employee::where('employment_status', 'active')
            ->where('qr_enabled', true)
            ->whereJsonContains('skills', $stageName)
            ->orderByRaw('current_workload ASC')
            ->orderByRaw('efficiency_rating DESC')
            ->limit($limit)
            ->get();
    }

    /**
     * Reassign stage to different employee
     */
    public function reassignStage(ProductionStage $stage, Employee $newEmployee)
    {
        // Decrease workload of current employee
        if ($stage->employee) {
            $stage->employee->decrement('current_workload');
        }

        // Assign to new employee
        $stage->employee_id = $newEmployee->id;
        $stage->status = 'assigned';
        $stage->save();

        // Increase workload of new employee
        $newEmployee->increment('current_workload');

        return $stage;
    }

    /**
     * Get workload statistics
     */
    public function getWorkloadStats()
    {
        $employees = Employee::where('employment_status', 'active')->get();

        return [
            'total_employees' => $employees->count(),
            'available_employees' => $employees->where('current_workload', 0)->count(),
            'busy_employees' => $employees->where('current_workload', '>', 0)->count(),
            'overloaded_employees' => $employees->where('current_workload', '>', 3)->count(),
            'average_workload' => $employees->avg('current_workload'),
        ];
    }
}
