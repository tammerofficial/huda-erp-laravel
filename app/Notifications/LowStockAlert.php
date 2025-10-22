<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Material;

class LowStockAlert extends Notification
{
    use Queueable;

    protected $material;

    /**
     * Create a new notification instance.
     */
    public function __construct(Material $material)
    {
        $this->material = $material;
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
            ->subject('تنبيه: مخزون منخفض - ' . $this->material->name)
            ->line('المادة: ' . $this->material->name)
            ->line('الكمية المتبقية: ' . $this->material->current_stock)
            ->line('حد إعادة الطلب: ' . $this->material->reorder_level)
            ->action('عرض المادة', route('materials.show', $this->material))
            ->line('يرجى مراجعة المخزون وإعادة الطلب إذا لزم الأمر.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'material_id' => $this->material->id,
            'material_name' => $this->material->name,
            'current_stock' => $this->material->current_stock,
            'reorder_level' => $this->material->reorder_level,
            'message' => 'مخزون منخفض للمادة: ' . $this->material->name,
        ];
    }
}
