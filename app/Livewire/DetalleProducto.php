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

        $imagenesGaleria = $this->producto->imagenesGaleria()->get();
        $resenas         = $this->producto->resenasAprobadas()->latest()->limit(10)->get();
        $promedio        = $this->producto->promedioCalificacion();

        return view('livewire.detalle-producto', compact('relacionados', 'imagenesGaleria', 'resenas', 'promedio'))
            ->layout('layouts.app', [
                'titulo'      => $this->producto->nombre . ' — Tileo',
                'descripcion' => $this->producto->descripcion
                    ? mb_strimwidth(strip_tags($this->producto->descripcion), 0, 155, '…')
                    : 'Especia artesanal de Tileo. Elaborada con dedicación en Mercedes, Buenos Aires.',
                'ogImagen'    => $this->producto->imagen
                    ? asset('imagenes/' . $this->producto->imagen)
                    : null,
            ]);
    }
}
