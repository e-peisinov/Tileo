<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\Producto;
use Livewire\Component;
use Livewire\WithPagination;

class Catalogo extends Component
{
    use WithPagination;

    public string $categoriaActiva = 'todos';
    public string $busqueda = '';

    public function updatingBusqueda(): void { $this->resetPage(); }

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
            ->when($this->busqueda, fn($q) => $q->where('nombre', 'like', "%{$this->busqueda}%"))
            ->paginate(12);

        return view('livewire.catalogo', compact('productos', 'categorias'))
            ->layout('layouts.app', ['titulo' => 'Catálogo — Tileo']);
    }
}
