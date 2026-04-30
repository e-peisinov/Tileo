<?php

namespace App\Livewire;

use App\Models\Madera;
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
            $nuevaCantidad = min($nuevaCantidad, $producto->stock);
            if ($nuevaCantidad <= 0) {
                unset($carrito[$productoId]);
                session(['carrito' => $carrito]);
                $this->abierto = true;
                return;
            }
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
    }

    #[On('madera-configurada')]
    public function agregarMadera(int $maderaId, array $condimentos): void
    {
        $madera = Madera::find($maderaId);

        if (! $madera || ! $madera->activo) {
            return;
        }

        // Validar stock actual de cada condimento antes de agregar
        foreach ($condimentos as $condimento) {
            $producto = Producto::find($condimento['producto_id'] ?? null);
            if (! $producto || $producto->stock < ($condimento['cantidad'] ?? 1)) {
                return;
            }
        }

        $clave   = 'madera_' . time() . '_' . uniqid();
        $maderas = session('carrito_maderas', []);

        $maderas[$clave] = [
            'clave'       => $clave,
            'madera_id'   => $madera->id,
            'nombre'      => $madera->nombre,
            'capacidad'   => $madera->capacidad,
            'precio'      => (float) $madera->precio,
            'imagen'      => $madera->imagen,
            'condimentos' => $condimentos,
            'subtotal'    => (float) $madera->precio,
        ];

        session(['carrito_maderas' => $maderas]);
    }

    public function removerItem(int $productoId): void
    {
        $carrito = session('carrito', []);
        unset($carrito[$productoId]);
        session(['carrito' => $carrito]);
    }

    public function removerMadera(string $clave): void
    {
        $maderas = session('carrito_maderas', []);
        unset($maderas[$clave]);
        session(['carrito_maderas' => $maderas]);
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

        // Consultar stock actual en DB para no usar el valor guardado al agregar
        $stockActual = Producto::where('id', $productoId)->value('stock') ?? 0;
        $cantidad    = min($cantidad, $stockActual);

        $carrito[$productoId]['stock']    = $stockActual;
        $carrito[$productoId]['cantidad'] = $cantidad;
        $carrito[$productoId]['subtotal'] = $carrito[$productoId]['precio'] * $cantidad;

        session(['carrito' => $carrito]);
    }

    public function vaciarCarrito(): void
    {
        session()->forget('carrito');
        session()->forget('carrito_maderas');
    }

    public function obtenerItems(): array
    {
        return session('carrito', []);
    }

    public function obtenerMaderas(): array
    {
        return session('carrito_maderas', []);
    }

    public function obtenerTotal(): float
    {
        $totalProductos = collect(session('carrito', []))->sum('subtotal');
        $totalMaderas   = collect(session('carrito_maderas', []))->sum('subtotal');
        return $totalProductos + $totalMaderas;
    }

    public function obtenerCantidadTotal(): int
    {
        $cantProductos = collect(session('carrito', []))->sum('cantidad');
        $cantMaderas   = count(session('carrito_maderas', []));
        return $cantProductos + $cantMaderas;
    }

    public function render()
    {
        return view('livewire.carrito', [
            'items'         => $this->obtenerItems(),
            'maderas'       => $this->obtenerMaderas(),
            'total'         => $this->obtenerTotal(),
            'cantidadTotal' => $this->obtenerCantidadTotal(),
        ]);
    }
}
