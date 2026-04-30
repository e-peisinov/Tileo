<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\Madera;
use App\Models\Producto;
use Livewire\Component;

class ConfiguradorMadera extends Component
{
    public Madera $madera;
    public array $cantidades = [];
    public string $busqueda = '';
    public string $categoriaActiva = 'todos';
    public bool $agregado = false;

    public function mount(Madera $madera): void
    {
        abort_unless($madera->activo, 404);
        $this->madera = $madera;
    }

    public function incrementar(int $productoId): void
    {
        $producto = Producto::find($productoId);
        if (! $producto || ! $producto->activo || $producto->stock < 1) {
            return;
        }

        $actual = $this->cantidades[$productoId] ?? 0;
        $totalActual = array_sum($this->cantidades);

        if ($totalActual >= $this->madera->capacidad) {
            return;
        }

        if ($actual >= $producto->stock) {
            return;
        }

        $this->cantidades[$productoId] = $actual + 1;
    }

    public function decrementar(int $productoId): void
    {
        $actual = $this->cantidades[$productoId] ?? 0;
        if ($actual <= 0) {
            return;
        }
        $this->cantidades[$productoId] = $actual - 1;
        if ($this->cantidades[$productoId] === 0) {
            unset($this->cantidades[$productoId]);
        }
    }

    public function totalSeleccionado(): int
    {
        return array_sum($this->cantidades);
    }

    public function agregarAlCarrito(): void
    {
        if ($this->totalSeleccionado() !== $this->madera->capacidad) {
            return;
        }

        $condimentos = [];
        foreach ($this->cantidades as $productoId => $cantidad) {
            if ($cantidad > 0) {
                $producto = Producto::find($productoId);
                if ($producto) {
                    $condimentos[] = [
                        'producto_id' => (int) $productoId,
                        'nombre'      => $producto->nombre,
                        'cantidad'    => $cantidad,
                    ];
                }
            }
        }

        $this->dispatch('madera-configurada', maderaId: $this->madera->id, condimentos: $condimentos);
        $this->cantidades = [];
        $this->agregado = true;
    }

    public function render()
    {
        $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();

        $productos = Producto::with('categorias')
            ->where('activo', true)
            ->where('stock', '>', 0)
            ->when($this->categoriaActiva !== 'todos', function ($q) {
                $q->whereHas('categorias', fn ($sub) => $sub->where('categorias.nombre', $this->categoriaActiva));
            })
            ->when($this->busqueda, fn ($q) => $q->where('nombre', 'like', "%{$this->busqueda}%"))
            ->orderBy('nombre')
            ->get();

        return view('livewire.configurador-madera', [
            'productos'         => $productos,
            'categorias'        => $categorias,
            'totalSeleccionado' => $this->totalSeleccionado(),
        ])->layout('layouts.app', [
            'titulo'      => 'Configurar ' . $this->madera->nombre . ' — Tileo',
            'descripcion' => 'Elegí los ' . $this->madera->capacidad . ' condimentos para tu ' . $this->madera->nombre . '.',
        ]);
    }
}
