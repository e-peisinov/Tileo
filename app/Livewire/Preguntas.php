<?php

namespace App\Livewire;

use App\Models\Contenido;
use Livewire\Component;

class Preguntas extends Component
{
    public function render()
    {
        $faqsJson = Contenido::obtener('preguntas_frecuentes', '[]');
        $faqs     = json_decode($faqsJson, true) ?: [];

        return view('livewire.preguntas', compact('faqs'))
            ->layout('layouts.app', [
                'titulo'      => 'Preguntas Frecuentes — Tileo',
                'descripcion' => 'Respondemos las dudas más frecuentes sobre los productos, formas de pago, envíos y pedidos de Tileo.',
            ]);
    }
}
