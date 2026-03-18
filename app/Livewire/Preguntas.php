<?php

namespace App\Livewire;

use Livewire\Component;

class Preguntas extends Component
{
    public function render()
    {
        return view('livewire.preguntas')
            ->layout('layouts.app', ['titulo' => 'Preguntas Frecuentes — Tileo']);
    }
}
