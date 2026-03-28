<?php

namespace App\Observers;

use App\Mail\AvisoStockMail;
use App\Mail\StockAgotadoMail;
use App\Models\AvisoStock;
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

        // Stock se repuso → avisar a los suscriptores (queue para no bloquear el request)
        if ((int) $stockNuevo > 0 && (int) $stockAnterior === 0) {
            AvisoStock::where('producto_id', $producto->id)
                ->where('enviado', false)
                ->each(function (AvisoStock $aviso) use ($producto) {
                    try {
                        Mail::to($aviso->email)->queue(new AvisoStockMail($producto, $aviso));
                        $aviso->update(['enviado' => true, 'enviado_en' => now()]);
                    } catch (\Exception $e) {
                        \Log::error('Error al enviar aviso de stock: ' . $e->getMessage());
                    }
                });
        }
    }
}
