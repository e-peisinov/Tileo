<?php

namespace App\Livewire;

use Livewire\Component;

class Nosotros extends Component
{
    public function render()
    {
        return view('livewire.nosotros')
            ->layout('layouts.app', ['titulo' => 'Nosotros — Tileo']);
    }
}
