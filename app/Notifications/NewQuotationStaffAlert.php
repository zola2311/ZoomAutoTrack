<?php

namespace App\Notifications;

use App\Models\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewQuotationStaffAlert extends Notification
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
        $recipientName = property_exists($notifiable, 'name') && $notifiable->name ? $notifiable->name : 'Service Team';
        $vehicle = $q->vehicle_display_name;
        $urgencyLabel = match ($q->urgency) {
            'urgent_asap' => 'URGENT (ASAP)',
            'within_week' => 'Within This Week',
            default       => 'Flexible Timeline',
        };

        $photoCount = $q->defectPhotos()->count();
        $symptomsText = !empty($q->symptoms) ? implode(', ', $q->symptoms) : 'None specified';
        $voiceNoteText = $q->hasVoiceNote() ? '🎙️ Yes (Voice note recorded by customer)' : 'No';
        $descriptionText = $q->description ?: 'No written text provided (refer to selected symptoms & voice note).';

        return (new MailMessage)
            ->subject("Zoom EV: New Quotation & Price Inquiry — #{$q->quotation_number} ({$vehicle})")
            ->greeting("Hello {$recipientName},")
            ->line("A customer has submitted a new **repair & price quotation request** on Zoom EV.")
            ->line("**Quotation Reference:** #{$q->quotation_number}")
            ->line("**Customer Details:**")
            ->line("• **Name:** {$q->customer_name}")
            ->line("• **Phone:** {$q->phone}")
            ->line("• **Email:** {$q->email}")
            ->line("**Vehicle Details:**")
            ->line("• **Vehicle:** {$vehicle}")
            ->line("• **Category:** {$q->service_category}")
            ->line("• **Drivable Condition:** " . ucfirst(str_replace('_', ' ', $q->drivable_status)))
            ->line("• **Urgency:** {$urgencyLabel}")
            ->line("• **Attached Defect Photos:** {$photoCount} photo(s) uploaded")
            ->line("• **Voice Note Audio:** {$voiceNoteText}")
            ->line("**Symptoms Observed:** {$symptomsText}")
            ->line("**Customer's Problem Description:**")
            ->line('"' . $descriptionText . '"')
            ->action('Review & Formulate Quotation in Admin', url("/admin/quotations/{$q->id}"))
            ->line('Please review the defect photos, listen to any voice notes, and formulate the diagnostic assessment and cost breakdown.');
    }
}
