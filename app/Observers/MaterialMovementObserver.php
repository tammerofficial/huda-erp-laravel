<?php

namespace App\Observers;

use App\Models\InventoryMovement;
use App\Services\AccountingService;

class MaterialMovementObserver
{
    /**
     * Handle the InventoryMovement "created" event.
     */
    public function created(InventoryMovement $movement): void
    {
        // Create accounting entry for material consumption
        if ($movement->movement_type === 'consumption') {
            $this->createConsumptionEntry($movement);
        }
        
        // Create accounting entry for material purchase
        if ($movement->movement_type === 'purchase') {
            $this->createPurchaseEntry($movement);
        }
    }

    /**
     * Create accounting entry for material consumption
     */
    protected function createConsumptionEntry(InventoryMovement $movement): void
    {
        try {
            $accountingService = app(AccountingService::class);
            
            $totalCost = $movement->quantity * $movement->material->unit_cost;
            
            $accountingService->recordMaterialConsumption(
                $movement->material,
                $totalCost,
                $movement->reference_type,
                $movement->reference_id
            );
        } catch (\Exception $e) {
            \Log::error('Failed to create consumption entry: ' . $e->getMessage());
        }
    }

    /**
     * Create accounting entry for material purchase
     */
    protected function createPurchaseEntry(InventoryMovement $movement): void
    {
        try {
            $accountingService = app(AccountingService::class);
            
            $totalCost = $movement->quantity * $movement->material->unit_cost;
            
            $accountingService->recordMaterialPurchase(
                $movement->material,
                $totalCost,
                $movement->reference_type,
                $movement->reference_id
            );
        } catch (\Exception $e) {
            \Log::error('Failed to create purchase entry: ' . $e->getMessage());
        }
    }
}
