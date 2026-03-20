<?php

namespace App\Livewire;

use Livewire\Component;

class Privacidad extends Component
{
    public function render()
    {
        return view('livewire.privacidad')
            ->layout('layouts.app', ['titulo' => 'Política de Privacidad — Tileo']);
    }
}
