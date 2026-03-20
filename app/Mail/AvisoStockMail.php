<?php

namespace App\Mail;

use App\Models\AvisoStock;
use App\Models\Producto;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AvisoStockMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Producto $producto,
        public AvisoStock $aviso,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡' . $this->producto->nombre . ' ya tiene stock! · Tileo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.aviso-stock',
        );
    }
}
