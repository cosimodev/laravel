<?php

namespace App\Mail;

use App\Models\Workshop;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkshopReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Workshop $workshop) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Promemoria: {$this->workshop->title} - domani",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.workshop-reminder',
        );
    }
}
