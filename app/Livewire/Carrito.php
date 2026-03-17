<?php

namespace App\Livewire;

use App\Models\Producto;
use Livewire\Attributes\On;
use Livewire\Component;

class Carrito extends Component
{
    public bool $abierto = false;

    public function abrirCarrito(): void
    {
        $this->abierto = true;
    }

    public function cerrarCarrito(): void
    {
        $this->abierto = false;
    }

    #[On('producto-agregado')]
    public function agregarProducto(int $productoId, int $cantidad = 1): void
    {
        $producto = Producto::find($productoId);

        if (! $producto || ! $producto->activo || $producto->stock < 1) {
            return;
        }

        $carrito = session('carrito', []);

        if (isset($carrito[$productoId])) {
            $nuevaCantidad = $carrito[$productoId]['cantidad'] + $cantidad;
            // No superar el stock disponible
            $nuevaCantidad = min($nuevaCantidad, $producto->stock);
            $carrito[$productoId]['cantidad'] = $nuevaCantidad;
            $carrito[$productoId]['subtotal'] = $producto->precio * $nuevaCantidad;
        } else {
            $carrito[$productoId] = [
                'id'       => $producto->id,
                'nombre'   => $producto->nombre,
                'precio'   => $producto->precio,
                'cantidad' => $cantidad,
                'subtotal' => $producto->precio * $cantidad,
                'imagen'   => $producto->imagen,
                'unidad'   => $producto->unidad,
                'stock'    => $producto->stock,
            ];
        }

        session(['carrito' => $carrito]);
        $this->abierto = true;
    }

    public function removerItem(int $productoId): void
    {
        $carrito = session('carrito', []);
        unset($carrito[$productoId]);
        session(['carrito' => $carrito]);
    }

    public function actualizarCantidad(int $productoId, int $cantidad): void
    {
        $carrito = session('carrito', []);

        if (! isset($carrito[$productoId])) {
            return;
        }

        if ($cantidad <= 0) {
            $this->removerItem($productoId);
            return;
        }

        $stock = $carrito[$productoId]['stock'];
        $cantidad = min($cantidad, $stock);
        $carrito[$productoId]['cantidad'] = $cantidad;
        $carrito[$productoId]['subtotal'] = $carrito[$productoId]['precio'] * $cantidad;

        session(['carrito' => $carrito]);
    }

    public function vaciarCarrito(): void
    {
        session()->forget('carrito');
    }

    public function obtenerItems(): array
    {
        return session('carrito', []);
    }

    public function obtenerTotal(): float
    {
        return collect(session('carrito', []))->sum('subtotal');
    }

    public function obtenerCantidadTotal(): int
    {
        return collect(session('carrito', []))->sum('cantidad');
    }

    public function render()
    {
        return view('livewire.carrito', [
            'items'          => $this->obtenerItems(),
            'total'          => $this->obtenerTotal(),
            'cantidadTotal'  => $this->obtenerCantidadTotal(),
        ]);
    }
}
