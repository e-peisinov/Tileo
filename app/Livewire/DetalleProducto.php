<?php

namespace App\Livewire;

use App\Models\Producto;
use Livewire\Component;

class DetalleProducto extends Component
{
    public Producto $producto;

public function render()
    {
        $categoriaIds = $this->producto->categorias->pluck('id');

        $relacionados = Producto::with('categorias')
            ->where('activo', true)
            ->where('id', '!=', $this->producto->id)
            ->when($categoriaIds->isNotEmpty(), fn($q) =>
                $q->whereHas('categorias', fn($sub) => $sub->whereIn('categorias.id', $categoriaIds))
            )
            ->limit(3)
            ->get();

        $imagenesGaleria = $this->producto->imagenesGaleria()->get();

        return view('livewire.detalle-producto', compact('relacionados', 'imagenesGaleria'))
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
