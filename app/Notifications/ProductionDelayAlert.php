<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ProductionOrder;

class ProductionDelayAlert extends Notification
{
    use Queueable;

    protected $productionOrder;

    /**
     * Create a new notification instance.
     */
    public function __construct(ProductionOrder $productionOrder)
    {
        $this->productionOrder = $productionOrder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $daysOverdue = now()->diffInDays($this->productionOrder->expected_completion_date);
        
        return (new MailMessage)
            ->subject('Production Delay Alert - Order #' . $this->productionOrder->id)
            ->line('Production Order: #' . $this->productionOrder->id)
            ->line('Product: ' . $this->productionOrder->product->name)
            ->line('Quantity: ' . $this->productionOrder->quantity . ' pieces')
            ->line('Expected Completion: ' . $this->productionOrder->expected_completion_date->format('Y-m-d'))
            ->line('Days Overdue: ' . $daysOverdue)
            ->action('View Order', route('productions.show', $this->productionOrder))
            ->line('Please take immediate action to complete this production order.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $daysOverdue = now()->diffInDays($this->productionOrder->expected_completion_date);
        
        return [
            'production_order_id' => $this->productionOrder->id,
            'product_name' => $this->productionOrder->product->name,
            'quantity' => $this->productionOrder->quantity,
            'expected_completion' => $this->productionOrder->expected_completion_date->format('Y-m-d'),
            'days_overdue' => $daysOverdue,
            'message' => 'Production delay for Order #' . $this->productionOrder->id . ' (' . $daysOverdue . ' days overdue)',
        ];
    }
}
