<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    use Queueable;

    public function __construct(public Invoice $invoice, public float $amountPaidNow) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment received — '.$this->invoice->invoice_number)
            ->greeting('Hi, '.$notifiable->full_name.'!')
            ->line('We\'ve received your payment in full for invoice '.$this->invoice->invoice_number.'.')
            ->line('Amount paid: '.number_format($this->amountPaidNow, 2).' ETB')
            ->line('Total paid: '.number_format($this->invoice->total, 2).' ETB')
            ->line('Thank you for your business.');
    }
}
