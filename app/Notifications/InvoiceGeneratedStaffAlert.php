<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceGeneratedStaffAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Invoice $invoice) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $inv = $this->invoice;
        $inv->loadMissing(['customer', 'jobCard.vehicle', 'branch']);
        $vehicle = $inv->jobCard?->vehicle;
        $customer = $inv->customer;
        $recipientName = property_exists($notifiable, 'name') && $notifiable->name ? $notifiable->name : 'Finance Staff';

        $totalFormatted = number_format($inv->total, 2) . ' ETB';
        $jobRef = $inv->jobCard?->job_number ? "#{$inv->jobCard->job_number}" : "Job Card #{$inv->job_card_id}";

        return (new MailMessage)
            ->subject("Zoom EV: New Invoice Ready for Collection — {$inv->invoice_number} ({$totalFormatted})")
            ->greeting("Hello {$recipientName},")
            ->line("A work order has been completed and an invoice is now pending collection and settlement.")
            ->line("**Invoice Reference:** {$inv->invoice_number}")
            ->line("**Work Order:** {$jobRef}")
            ->line("**Customer:** {$customer?->full_name} ({$customer?->phone})")
            ->line("**Vehicle:** {$vehicle?->plate_number} — {$vehicle?->year} {$vehicle?->make} {$vehicle?->model}")
            ->line("**Total Amount Due:** {$totalFormatted}")
            ->action('Open Invoice in Cashier Portal', url("/admin/invoices/{$inv->id}"))
            ->line('Please verify payments, apply any authorized discounts or loyalty points, and record receipt upon vehicle delivery.');
    }
}
