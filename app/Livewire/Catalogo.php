<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\Producto;
use Livewire\Component;

class Catalogo extends Component
{
    public string $categoriaActiva = 'todos';

    public function agregarAlCarrito(int $productoId): void
    {
        $this->dispatch('producto-agregado', productoId: $productoId);
    }

    public function render()
    {
        $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();

        $productos = Producto::with('categoria')
            ->where('activo', true)
            ->when($this->categoriaActiva !== 'todos', function ($q) {
                $q->whereHas('categoria', fn($sub) =>
                    $sub->where('nombre', $this->categoriaActiva)
                );
            })
            ->get();

        return view('livewire.catalogo', compact('productos', 'categorias'))
            ->layout('layouts.app', ['titulo' => 'Catálogo — Tileo']);
    }
}
