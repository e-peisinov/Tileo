<?php

namespace App\Livewire;

use App\Mail\ContactoMail;
use App\Mail\ConfirmacionContactoMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\Attributes\Validate;

class Contacto extends Component
{
    #[Validate('required|min:2')]
    public string $nombre = '';

    #[Validate('required|email|max:150')]
    public string $email = '';

    #[Validate('required|min:6|max:30')]
    public string $telefono = '';

    #[Validate('nullable|max:150')]
    public string $asunto = '';

    #[Validate('required|min:10')]
    public string $mensaje = '';

    public bool $enviado = false;

    public function enviar(): void
    {
        $this->validate();

        $emailAdmin = config('tileo.email_admin');
        try {
            Mail::to($emailAdmin)->send(new ContactoMail(
                $this->nombre,
                $this->email,
                $this->telefono,
                $this->asunto,
                $this->mensaje,
            ));
        } catch (\Exception $e) {
            \Log::error('Error al enviar mensaje de contacto: ' . $e->getMessage());
            $this->addError('mensaje', 'No se pudo enviar tu mensaje. Intentá de nuevo en unos minutos.');
            return;
        }

        // Confirmación al cliente (fallo no crítico)
        try {
            Mail::to($this->email)->send(new ConfirmacionContactoMail(
                $this->nombre,
                $this->asunto,
            ));
        } catch (\Exception $e) {
            \Log::error('Error al enviar confirmación de contacto al cliente: ' . $e->getMessage());
        }

        $this->enviado = true;
    }

    public function render()
    {
        return view('livewire.contacto')
            ->layout('layouts.app', [
                'titulo'      => 'Contacto — Tileo',
                'descripcion' => 'Contactate con Tileo. Respondemos consultas sobre productos, pedidos y próximas ferias artesanales de Mercedes, Buenos Aires.',
            ]);
    }
}
