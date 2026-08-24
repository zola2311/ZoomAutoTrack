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

        return (new MailMessage)
            ->subject($isDue
                ? "Service due — {$vehicle->plate_number}"
                : "Service coming up — {$vehicle->plate_number}")
            ->greeting('Hi, '.$notifiable->full_name.'!')
            ->line($isDue
                ? "Your {$vehicle->make} {$vehicle->model} ({$vehicle->plate_number}) is due for its next service."
                : "Your {$vehicle->make} {$vehicle->model} ({$vehicle->plate_number}) will need service soon — "
                .number_format($this->due['km_remaining']).' km remaining.')
            ->line('Book a visit at your earliest convenience to keep your service history up to date.')
            ->action('View vehicle', $vehicle->passportUrl());
    }
}
