<?php

namespace App\Notifications;

use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceDueReminder extends Notification
{
    use Queueable;

    public function __construct(public Vehicle $vehicle, public array $due) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $vehicle = $this->vehicle;
        $isDue = $this->due['is_due'];

        $subject = $isDue
            ? 'Service overdue — ' . $vehicle->plate_number
            : 'Service coming up — ' . $vehicle->plate_number;

        $line1 = $isDue
            ? 'Your **' . $vehicle->make . ' ' . $vehicle->model . '** (' . $vehicle->plate_number . ') is overdue for its next service.'
            : 'Your **' . $vehicle->make . ' ' . $vehicle->model . '** (' . $vehicle->plate_number . ') is approaching its next service — only **' . number_format($this->due['km_remaining']) . ' km** remaining.';

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hi ' . $notifiable->full_name . ',')
            ->line($line1)
            ->line('Keeping up with regular service protects your vehicle and maintains its resale value.')
            ->action('View vehicle history', $vehicle->passportUrl())
            ->line('Book an appointment to schedule your next service.')
            ->line('Thank you for choosing AutoTrack Ethiopia.');
    }
}
