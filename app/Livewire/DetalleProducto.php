<?php

namespace App\Livewire;

use App\Models\Producto;
use Livewire\Component;

class DetalleProducto extends Component
{
    public Producto $producto;

    public function agregarAlCarrito(): void
    {
        $this->dispatch('producto-agregado', productoId: $this->producto->id);
    }

    public function render()
    {
        $relacionados = Producto::with('categoria')
            ->where('activo', true)
            ->where('categoria_id', $this->producto->categoria_id)
            ->where('id', '!=', $this->producto->id)
            ->limit(3)
            ->get();

        return view('livewire.detalle-producto', compact('relacionados'))
            ->layout('layouts.app', ['titulo' => $this->producto->nombre . ' — Tileo']);
    }
}
