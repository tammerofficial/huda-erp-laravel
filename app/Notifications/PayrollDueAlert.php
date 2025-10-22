<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Payroll;

class PayrollDueAlert extends Notification
{
    use Queueable;

    protected $payroll;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payroll $payroll)
    {
        $this->payroll = $payroll;
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
            ->subject('Payroll Due Alert - ' . $this->payroll->employee->user->name)
            ->line('Employee: ' . $this->payroll->employee->user->name)
            ->line('Period: ' . $this->payroll->period_start->format('Y-m-d') . ' to ' . $this->payroll->period_end->format('Y-m-d'))
            ->line('Amount: ' . number_format($this->payroll->total_amount, 3) . ' KWD')
            ->line('Status: ' . ucfirst($this->payroll->status))
            ->action('View Payroll', route('payroll.show', $this->payroll))
            ->line('Please process the payroll payment as soon as possible.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'payroll_id' => $this->payroll->id,
            'employee_name' => $this->payroll->employee->user->name,
            'amount' => $this->payroll->total_amount,
            'period_start' => $this->payroll->period_start->format('Y-m-d'),
            'period_end' => $this->payroll->period_end->format('Y-m-d'),
            'message' => 'Payroll due for ' . $this->payroll->employee->user->name,
        ];
    }
}
