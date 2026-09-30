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
            ->subject('You\'ve been added to AutoTrack Ethiopia')
            ->greeting('Hi ' . $notifiable->full_name . ',')
            ->line('A garage account has been created for you on **AutoTrack Ethiopia**.')
            ->line('You can now view your vehicle service history, book appointments, and track your loyalty points online.')
            ->action('Set your password', $url)
            ->line('This link expires in **60 minutes**.')
            ->line('If you weren\'t expecting this, you can safely ignore this email.');
    }
}
