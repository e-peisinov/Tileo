<?php

namespace App\Livewire;

use Livewire\Component;

class Catalogo extends Component
{
    public function render()
    {
        return view('livewire.catalogo')
            ->layout('layouts.app', ['titulo' => 'Catálogo — Tileo']);
    }
}
