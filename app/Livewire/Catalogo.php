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
    public string $ordenar = 'nombre_asc';
    public string $precioMin = '';
    public string $precioMax = '';
    public bool $soloConStock = false;
    public array $cantidades = []; // cantidad seleccionada por producto_id

    public function updatingBusqueda(): void { $this->resetPage(); }
    public function updatingOrdenar(): void { $this->resetPage(); }
    public function updatingPrecioMin(): void { $this->resetPage(); }
    public function updatingPrecioMax(): void { $this->resetPage(); }
    public function updatingSoloConStock(): void { $this->resetPage(); }

    public function agregarAlCarrito(int $productoId): void
    {
        $cantidad = (int) ($this->cantidades[$productoId] ?? 1);
        $cantidad = max(1, $cantidad);
        $this->dispatch('producto-agregado', productoId: $productoId, cantidad: $cantidad);
        // Resetear a 1 después de agregar
        $this->cantidades[$productoId] = 1;
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
            ->when($this->precioMin !== '', fn($q) => $q->where('precio', '>=', $this->precioMin))
            ->when($this->precioMax !== '', fn($q) => $q->where('precio', '<=', $this->precioMax))
            ->when($this->soloConStock, fn($q) => $q->where('stock', '>', 0))
            ->when($this->ordenar === 'nombre_asc', fn($q) => $q->orderBy('nombre'))
            ->when($this->ordenar === 'nombre_desc', fn($q) => $q->orderByDesc('nombre'))
            ->when($this->ordenar === 'precio_asc', fn($q) => $q->orderBy('precio'))
            ->when($this->ordenar === 'precio_desc', fn($q) => $q->orderByDesc('precio'))
            ->when($this->ordenar === 'recientes', fn($q) => $q->orderByDesc('created_at'))
            ->paginate(12);

        return view('livewire.catalogo', compact('productos', 'categorias'))
            ->layout('layouts.app', [
                'titulo'      => 'Catálogo — Tileo',
                'descripcion' => 'Explorá nuestro catálogo de hierbas, especias y condimentos artesanales. Filtrá por categoría, precio y stock. Envíos a todo el país.',
            ]);
    }
}
