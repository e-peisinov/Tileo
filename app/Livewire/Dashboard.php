<?php

namespace App\Livewire;

use App\Models\Banner;
use App\Models\Producto;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $productosDestacados = Producto::with('categorias')
            ->where('activo', true)
            ->where('destacado', true)
            ->orderBy('nombre')
            ->get();

        if ($productosDestacados->isEmpty()) {
            $productosDestacados = Producto::with('categorias')
                ->where('activo', true)
                ->orderBy('nombre')
                ->limit(6)
                ->get();
        }

        $banners = Banner::vigentes()->get();

        return view('livewire.dashboard', compact('productosDestacados', 'banners'))
            ->layout('layouts.app', [
                'titulo'      => 'Inicio — Tileo',
                'descripcion' => 'Hierbas, especias y condimentos artesanales elaborados con dedicación en Mercedes, Buenos Aires. Presentados en tubos de vidrio con tapa de corcho.',
            ]);
    }
}
