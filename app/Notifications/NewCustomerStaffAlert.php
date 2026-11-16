<?php

namespace App\Notifications;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCustomerStaffAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Customer $customer) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $c = $this->customer;
        $recipientName = property_exists($notifiable, 'name') && $notifiable->name ? $notifiable->name : 'Front Desk Staff';

        $code = $c->customer_code ?: 'CUST-' . str_pad($c->id, 4, '0', STR_PAD_LEFT);

        return (new MailMessage)
            ->subject("Zoom EV: New Customer Registered — {$c->full_name} ({$code})")
            ->greeting("Hello {$recipientName},")
            ->line("A new customer profile has just registered on **Zoom EV**.")
            ->line("**Customer Profile:**")
            ->line("• **Full Name:** {$c->full_name}")
            ->line("• **Phone Number:** {$c->phone}")
            ->line("• **Email:** " . ($c->email ?: 'Not provided'))
            ->line("• **Client Code:** {$code}")
            ->line("• **Referral Code:** " . ($c->referral_code ?: 'None'))
            ->action('View Customer Profile', url("/admin/customers/{$c->id}"))
            ->line('You can now link registered vehicles, view appointment requests, or open a job card.');
    }
}
