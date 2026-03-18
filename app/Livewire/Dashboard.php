<?php

namespace App\Livewire;

use App\Models\Producto;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $productosDestacados = Producto::with('categoria')
            ->where('activo', true)
            ->where('destacado', true)
            ->orderBy('nombre')
            ->get();

        if ($productosDestacados->isEmpty()) {
            $productosDestacados = Producto::with('categoria')
                ->where('activo', true)
                ->orderBy('nombre')
                ->limit(6)
                ->get();
        }

        return view('livewire.dashboard', compact('productosDestacados'))
            ->layout('layouts.app', ['titulo' => 'Inicio — Tileo']);
    }
}
