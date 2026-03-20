<?php

namespace App\Livewire;

use Livewire\Component;

class Privacidad extends Component
{
    public function render()
    {
        return view('livewire.privacidad')
            ->layout('layouts.app', [
                'titulo'      => 'Política de Privacidad — Tileo',
                'descripcion' => 'Política de privacidad de Tileo. Cómo recopilamos, usamos y protegemos tus datos personales.',
            ]);
    }
}
