<?php

namespace App\Notifications;

use App\Models\Appointment;
use Filament\Actions\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingStaffAlert extends Notification
{
    use Queueable;

    public function __construct(public Appointment $appointment) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $apt = $this->appointment;
        $customer = $apt->customer;
        $vehicle = $apt->vehicle;

        $servicesList = collect($apt->service_types ?? [])
            ->map(fn ($s) => ucwords(str_replace('_', ' ', $s)))
            ->join(', ');

        $dateFormatted = $apt->requested_date ? $apt->requested_date->format('l, F j, Y') : 'Not specified';
        $timeSlot = ucfirst($apt->requested_time_slot ?: 'Flexible / Any time');
        $recipientName = property_exists($notifiable, 'name') && $notifiable->name ? $notifiable->name : 'Administrator';

        $mail = (new MailMessage)
            ->subject("🚗 New Service Booking: {$vehicle?->plate_number} ({$customer?->full_name})")
            ->greeting("Hello {$recipientName},")
            ->line("A new service booking has just been requested on **Zoom EV**.")
            ->line("**Customer Details:**")
            ->line("• **Name:** {$customer?->full_name}")
            ->line("• **Phone:** {$customer?->phone}")
            ->line("• **Email:** " . ($customer?->email ?: 'None provided'))
            ->line("**Vehicle Details:**")
            ->line("• **Plate Number:** {$vehicle?->plate_number}")
            ->line("• **Vehicle:** {$vehicle?->make} {$vehicle?->model}" . ($vehicle?->year ? " ({$vehicle->year})" : ''))
            ->line("**Appointment Details:**")
            ->line("• **Requested Date:** {$dateFormatted}")
            ->line("• **Time Slot:** {$timeSlot}")
            ->line("• **Services:** " . ($servicesList ?: 'General Inspection'));

        if ($apt->other_service_description) {
            $mail->line("• **Other Service Description:** {$apt->other_service_description}");
        }

        if ($apt->notes) {
            $mail->line("• **Customer Notes:** {$apt->notes}");
        }

        return $mail
            ->action('View Appointments in Dashboard', url('/admin/appointments'))
            ->line('Please review the booking schedule and assign a service advisor or mechanic.');
    }
}
