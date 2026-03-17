<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;

class Contacto extends Component
{
    #[Validate('required|min:2')]
    public string $nombre = '';

    #[Validate('required|telefono')]
    public string $telefono = '';

    public string $asunto = '';

    #[Validate('required|min:10')]
    public string $mensaje = '';

    public bool $enviado = false;

    public function enviar(): void
    {
        $this->validate();

        // TODO: implementar envío de teléfono o guardado en BD

        $this->enviado = true;
    }

    public function render()
    {
        return view('livewire.contacto')
            ->layout('layouts.app', ['titulo' => 'Contacto — Tileo']);
    }
}
