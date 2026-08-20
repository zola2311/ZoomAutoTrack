<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerPasswordReset extends Notification
{
    use Queueable;

    public function __construct(public string $token) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = url(route('portal.password.set', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset your AutoTrack password')
            ->greeting('Hi, '.$notifiable->full_name.'!')
            ->line('We received a request to reset your password.')
            ->action('Reset your password', $url)
            ->line('This link expires in 60 minutes.')
            ->line('If you did not request this, no action is needed.');
    }
}
