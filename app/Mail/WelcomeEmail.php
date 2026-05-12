<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param  User $user
     */
    public function __construct(public User $user, public ?string $password)
    {
        // Currently Empty
    }

    /**
     * Get the message envelope.
     *
     * Defines the email subject line.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome Email',
        );
    }

    /**
     * Get the message content definition.
     *
     * Resolves the Blade view used to render the email body.
     *
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * No attachments are included with the welcome email.
     *
     * @return array<int,Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
