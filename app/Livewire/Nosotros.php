<?php

namespace App\Livewire;

use App\Models\Contenido;
use Livewire\Component;

class Nosotros extends Component
{
    public function render()
    {
        $historia = Contenido::obtener('nosotros_historia', '');

        return view('livewire.nosotros', compact('historia'))
            ->layout('layouts.app', [
                'titulo'      => 'Nosotros — Tileo',
                'descripcion' => 'Conocé la historia de Tileo, un emprendimiento familiar de hierbas, especias y condimentos artesanales de Mercedes, Buenos Aires.',
            ]);
    }
}
