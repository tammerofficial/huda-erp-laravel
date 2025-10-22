<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\AccountingService;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Check if status changed to 'completed'
        if ($order->isDirty('status') && $order->status === 'completed') {
            $this->processCompletedOrder($order);
        }
    }

    /**
     * Process completed order - create accounting entries
     */
    protected function processCompletedOrder(Order $order): void
    {
        try {
            // Only process if not already processed
            $existingEntry = \App\Models\Accounting::where('reference_type', 'order')
                ->where('reference_id', $order->id)
                ->where('category', 'sales')
                ->first();

            if ($existingEntry) {
                Log::info("Order #{$order->order_number} already has accounting entries, skipping.");
                return;
            }

            // Create accounting entries
            $accountingService = app(AccountingService::class);
            $accountingService->autoProcessOrder($order);

            Log::info("Accounting entries created for Order #{$order->order_number}");
        } catch (\Exception $e) {
            Log::error("Failed to create accounting entries for Order #{$order->order_number}: " . $e->getMessage());
        }
    }
}

