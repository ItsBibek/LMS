<?php

namespace App\Mail;

use App\Models\Issue;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FineWarningMail extends Mailable
{
    use Queueable, SerializesModels;

    public $issue;
    public $student;
    public $book;
    public $finePerDay = 2; // Rs. 2 per day

    /**
     * Create a new message instance.
     */
    public function __construct(Issue $issue)
    {
        $this->issue = $issue;
        $this->student = $issue->user;
        $this->book = $issue->book;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Fine Warning: Book Due Tomorrow',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.fine-warning',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
