<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ProductionOrder;

class QualityCheckRequired extends Notification
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
        return (new MailMessage)
            ->subject('Quality Check Required - Order #' . $this->productionOrder->id)
            ->line('Production Order: #' . $this->productionOrder->id)
            ->line('Product: ' . $this->productionOrder->product->name)
            ->line('Quantity: ' . $this->productionOrder->quantity . ' pieces')
            ->line('Created: ' . $this->productionOrder->created_at->format('Y-m-d H:i'))
            ->action('Inspect Order', route('quality-checks.inspect', $this->productionOrder))
            ->line('Please perform quality inspection as soon as possible.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'production_order_id' => $this->productionOrder->id,
            'product_name' => $this->productionOrder->product->name,
            'quantity' => $this->productionOrder->quantity,
            'created_at' => $this->productionOrder->created_at->format('Y-m-d H:i'),
            'message' => 'Quality check required for Order #' . $this->productionOrder->id,
        ];
    }
}
