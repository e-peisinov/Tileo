<?php

namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CambioEstadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Pedido $pedido,
        public string $estadoAnterior,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu pedido ' . $this->pedido->numero_pedido . ' — ' . $this->pedido->etiquetaEstado() . ' · Tileo',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.cambio-estado',
        );
    }
}
