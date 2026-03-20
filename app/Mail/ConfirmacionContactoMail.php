<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmacionContactoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nombreCliente,
        public string $asunto,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recibimos tu mensaje — Tileo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.confirmacion-contacto',
        );
    }
}
