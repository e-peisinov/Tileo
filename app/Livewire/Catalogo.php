<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\Producto;
use Livewire\Component;
use Livewire\WithPagination;

class Catalogo extends Component
{
    use WithPagination;

    public string $categoriaActiva = '0'; // '0' = todos; número = categoria_id
    public string $busqueda = '';
    public string $ordenar = 'nombre_asc';
    public bool $soloConStock = false;

    public function updatingBusqueda(): void { $this->resetPage(); }
    public function updatingOrdenar(): void { $this->resetPage(); }
    public function updatingSoloConStock(): void { $this->resetPage(); }

    public function render()
    {
        $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();

        $maderas = \App\Models\Madera::where('activo', true)->orderBy('capacidad')->get();

        $productos = Producto::with('categoria')
            ->where('activo', true)
            ->when((int) $this->categoriaActiva > 0, fn($q) =>
                $q->where('categoria_id', (int) $this->categoriaActiva)
            )
            ->when($this->busqueda, fn($q) => $q->where('nombre', 'like', "%{$this->busqueda}%"))
            ->when($this->soloConStock, fn($q) => $q->where('stock', '>', 0))
            ->when($this->ordenar === 'nombre_asc', fn($q) => $q->orderBy('nombre'))
            ->when($this->ordenar === 'nombre_desc', fn($q) => $q->orderByDesc('nombre'))
            ->when($this->ordenar === 'recientes', fn($q) => $q->orderByDesc('created_at'))
            ->paginate(12);

        return view('livewire.catalogo', compact('productos', 'categorias', 'maderas'))
            ->layout('layouts.app', [
                'titulo'      => 'Catálogo — Tileo',
                'descripcion' => 'Explorá nuestro catálogo de hierbas, especias y condimentos artesanales. Filtrá por categoría, precio y stock. Envíos a todo el país.',
            ]);
    }
}
