<?php

namespace App\Notifications;

use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class QuotationEstimateReadyNotification extends Notification
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
        $totalFormatted = number_format($q->estimated_total, 2) . ' ETB';
        $validUntilText = $q->valid_until ? $q->valid_until->format('M d, Y') : now()->addDays(14)->format('M d, Y');
        $durationText = $q->estimated_duration ?: '1-2 Business Days';

        $mail = (new MailMessage)
            ->subject("Official Quotation & Repair Assessment — #{$q->quotation_number} ({$vehicle})")
            ->greeting("Dear {$customerName},")
            ->line("Thank you for choosing **Zoom EV**. Our diagnostic and service engineering team has reviewed your quotation inquiry and defect documentation for your vehicle.")
            ->line("**Quotation Reference:** #{$q->quotation_number}")
            ->line("**Vehicle:** {$vehicle}")
            ->line("**Valid Until:** {$validUntilText}")
            ->line("**Estimated Completion Turnaround:** {$durationText}")
            ->line("---")
            ->line("### 🛠️ Diagnostic Assessment & Scope of Work");

        if ($q->diagnostic_assessment) {
            $mail->line($q->diagnostic_assessment);
        } else {
            $mail->line("Complete inspection, defect correction, and quality assessment as requested.");
        }

        $mail->line("---");

        // Labor items
        if (!empty($q->labor_items) && is_array($q->labor_items)) {
            $mail->line("### ⚙️ Estimated Labor & Services:");
            foreach ($q->labor_items as $labor) {
                $cost = number_format((float)($labor['estimated_cost'] ?? 0), 2) . ' ETB';
                $desc = $labor['description'] ?? 'Service Labor';
                $mail->line("• **{$desc}:** {$cost}");
            }
            if ($q->estimated_labor_total > 0) {
                $mail->line("**Labor Subtotal:** " . number_format($q->estimated_labor_total, 2) . " ETB");
            }
        }

        // Parts items
        if (!empty($q->parts_items) && is_array($q->parts_items)) {
            $mail->line("### 📦 Estimated Spare Parts & Materials:");
            foreach ($q->parts_items as $part) {
                $partName = $part['part_name'] ?? 'Replacement Part';
                $qty = $part['quantity'] ?? 1;
                $cost = number_format((float)($part['total'] ?? $part['estimated_cost'] ?? 0), 2) . ' ETB';
                $mail->line("• **{$partName} (Qty: {$qty}):** {$cost}");
            }
            if ($q->estimated_parts_total > 0) {
                $mail->line("**Parts Subtotal:** " . number_format($q->estimated_parts_total, 2) . " ETB");
            }
        }

        $mail->line("---")
            ->line("## 💰 Total Estimated Price: **{$totalFormatted}**")
            ->line("📎 **Official Itemized PDF Attached:** A formal PDF copy of this quotation is attached to this email for your records, vehicle history log, or insurance/fleet documentation.")
            ->line("*(Note: Final prices may adjust if hidden secondary defects are found during physical disassembly. All estimates are covered under our transparent quotation policy.)*")
            ->action('Accept & Schedule Your Service Booking', url('/book-a-service?quote=' . $q->quotation_number))
            ->line('If you have any questions or would like to discuss this quotation with our Service Advisor, call us directly at **+251 911 000 000** or reply to this email.')
            ->line('Thank you for trusting Zoom EV for certified automotive care.');

        // Generate and attach official Quotation PDF
        try {
            $pdf = Pdf::loadView('pdf.quotation-estimate', ['quotation' => $q]);
            $pdfContent = $pdf->output();

            $mail->attachData($pdfContent, "Quotation_{$q->quotation_number}_Zoom_EV.pdf", [
                'mime' => 'application/pdf',
            ]);
        } catch (\Throwable $e) {
            Log::error("Failed generating Quotation PDF for #{$q->quotation_number}: " . $e->getMessage());
        }

        return $mail;
    }
}
