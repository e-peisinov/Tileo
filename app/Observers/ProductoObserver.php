<?php

namespace App\Observers;

use App\Mail\StockAgotadoMail;
use App\Models\Producto;
use Illuminate\Support\Facades\Mail;

class ProductoObserver
{
    public function updated(Producto $producto): void
    {
        if (! $producto->isDirty('stock')) {
            return;
        }

        $stockAnterior = $producto->getOriginal('stock');
        $stockNuevo    = $producto->stock;

        // Stock se agotó → avisar al admin
        if ((int) $stockNuevo === 0 && (int) $stockAnterior > 0) {
            try {
                Mail::to(config('tileo.email_admin'))
                    ->send(new StockAgotadoMail($producto));
            } catch (\Exception $e) {
                \Log::error('Error al enviar email stock agotado: ' . $e->getMessage());
            }
        }

    }
}
