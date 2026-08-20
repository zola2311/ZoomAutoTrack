<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerInvitation extends Notification
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
            ->subject('Set up your AutoTrack account')
            ->greeting('Welcome, '.$notifiable->full_name.'!')
            ->line('A garage account has been created for you. Set a password to view your vehicle history and service records online.')
            ->action('Set your password', $url)
            ->line('This link expires in 60 minutes.');
    }
}
