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
        $date = $this->appointment->requested_date->format('d M Y');
        $time = $this->appointment->requested_time_slot
            ? ' — ' . ucfirst($this->appointment->requested_time_slot)
            : '';

        return (new MailMessage)
            ->subject('Appointment confirmed — ' . $date)
            ->greeting('Hi ' . $notifiable->full_name . ',')
            ->line('Your appointment has been confirmed.')
            ->line('**Vehicle:** ' . $vehicle->plate_number . ' — ' . $vehicle->make . ' ' . $vehicle->model)
            ->line('**Date:** ' . $date . $time)
            ->when(
                $this->appointment->jobCard,
                fn ($mail) => $mail->line('**Job card:** ' . $this->appointment->jobCard->job_number)
            )
            ->action('View your appointments', route('portal.appointments.index'))
            ->line('Please arrive on time. If you need to reschedule, contact us as early as possible.')
            ->line('Thank you for choosing AutoTrack Ethiopia.');
    }
}
