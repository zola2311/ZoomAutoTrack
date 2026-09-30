<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormSubmitted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $senderName,
        public string $senderEmail,
        public ?string $senderPhone,
        public string $messageBody,
    ) {}

    public function build(): self
    {
        return $this
            ->subject('New contact form message — AutoTrack Ethiopia')
            ->replyTo($this->senderEmail, $this->senderName)
            ->markdown('emails.contact-form');
    }
}
