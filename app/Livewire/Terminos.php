<?php

namespace App\Livewire;

use Livewire\Component;

class Terminos extends Component
{
    public function render()
    {
        return view('livewire.terminos')
            ->layout('layouts.app', ['titulo' => 'Términos y Condiciones — Tileo']);
    }
}
