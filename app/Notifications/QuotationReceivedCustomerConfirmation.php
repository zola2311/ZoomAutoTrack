<?php

namespace App\Notifications;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuotationReceivedCustomerConfirmation extends Notification
{
    use Queueable;

    public function __construct(public Quotation $quotation) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $q = $this->quotation;
        $customerName = $q->customer_name ?: 'Valued Customer';
        $vehicle = $q->vehicle_display_name;

        return (new MailMessage)
            ->subject("Quotation Request Received — Reference #{$q->quotation_number} ({$vehicle})")
            ->greeting("Hello {$customerName},")
            ->line("We have successfully received your price quotation and defect assessment request for your **{$vehicle}**.")
            ->line("**Quotation Reference Number:** #{$q->quotation_number}")
            ->line("**Service Category:** {$q->service_category}")
            ->line("**What happens next?**")
            ->line("1. Our certified automotive technicians and service advisors are reviewing your vehicle specifications, symptoms, and uploaded defect photos.")
            ->line("2. We will prepare an official report with an assessment of works required and transparent estimated prices for parts and labor.")
            ->line("3. You will receive the comprehensive quotation report via this email.")
            ->line('If you need immediate assistance or roadside support, please call our workshop hotline at **+251 911 000 000**.')
            ->line('Thank you for choosing Zoom EV.');
    }
}
