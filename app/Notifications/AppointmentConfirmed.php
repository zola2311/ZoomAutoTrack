<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentConfirmed extends Notification
{
    use Queueable;

    public function __construct(public Appointment $appointment) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $vehicle = $this->appointment->vehicle;

        return (new MailMessage)
            ->subject('Your appointment is confirmed')
            ->greeting('Hi, '.$notifiable->full_name.'!')
            ->line('Your appointment for '.$vehicle->plate_number.' ('.$vehicle->make.' '.$vehicle->model.') has been confirmed.')
            ->line('Date: '.$this->appointment->requested_date->format('d M Y')
                .($this->appointment->requested_time_slot ? ' — '.ucfirst($this->appointment->requested_time_slot) : ''))
            ->when($this->appointment->jobCard, fn ($mail) => $mail->line('Job card: '.$this->appointment->jobCard->job_number))
            ->line('We look forward to seeing you.');
    }
}
