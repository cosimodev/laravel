<?php

namespace App\Mail;

use App\Models\Workshop;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Email di promemoria inviata il giorno prima del workshop
 * a tutti i partecipanti con iscrizione confermata.
 *
 * Il template markdown si trova in resources/views/emails/workshop-reminder.blade.php
 * e include titolo, data, durata e descrizione del workshop.
 */
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
