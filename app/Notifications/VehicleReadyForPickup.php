<?php

namespace App\Notifications;

use App\Models\JobCard;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VehicleReadyForPickup extends Notification
{
    use Queueable;

    public function __construct(public JobCard $jobCard) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $vehicle = $this->jobCard->vehicle;

        return (new MailMessage)
            ->subject('Your vehicle is ready for pickup — ' . $vehicle->plate_number)
            ->greeting('Hi ' . $notifiable->full_name . ',')
            ->line('Great news! Your **' . $vehicle->make . ' ' . $vehicle->model . '** (' . $vehicle->plate_number . ') is ready for pickup.')
            ->line('Job card: **' . $this->jobCard->job_number . '**')
            ->action('View service details', $vehicle->passportUrl())
            ->line('Please collect your vehicle at your earliest convenience.')
            ->line('Thank you for choosing AutoTrack Ethiopia.');
    }
}
