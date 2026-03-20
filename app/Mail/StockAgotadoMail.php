<?php

namespace App\Mail;

use App\Models\Producto;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StockAgotadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Producto $producto,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Stock agotado: ' . $this->producto->nombre . ' · Tileo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.stock-agotado-admin',
        );
    }
}
