<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class CustomerVerifyEmail extends Notification
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = URL::temporarySignedRoute(
            'portal.verification.verify',
            now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        return (new MailMessage)
            ->subject('Verify your AutoTrack Ethiopia account')
            ->greeting('Hi ' . $notifiable->full_name . ',')
            ->line('Thanks for signing up! Please verify your email address to access your vehicle history, book appointments, and earn loyalty points.')
            ->action('Verify my email', $url)
            ->line('This link expires in **60 minutes**.')
            ->line('If you didn\'t create an account, no further action is required.');
    }
}
