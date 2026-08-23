<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    /** @param  array<string, string>  $data */
    public function __construct(public array $data) {}

    public function envelope(): Envelope
    {
        $subject = trim($this->data['subject'] ?? '') ?: 'New contact message';

        return new Envelope(
            subject: $subject.' – '.site_name(),
            replyTo: [$this->data['email']],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact.message',
            with: [
                'data' => $this->data,
                'siteName' => site_name(),
            ],
        );
    }
}
