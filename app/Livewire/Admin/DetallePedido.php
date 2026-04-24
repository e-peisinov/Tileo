<?php

namespace App\Livewire\Admin;

use App\Models\Pedido;
use App\Models\PedidoHistorialEstado;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DetallePedido extends Component
{
    public Pedido $pedido;

    public string $estado = '';
    public string $notas_admin = '';
    public string $costo_envio = '';
    public bool $guardado = false;

    public function updatedEstado(): void { $this->guardado = false; }
    public function updatedNotasAdmin(): void { $this->guardado = false; }
    public function updatedCostoEnvio(): void { $this->guardado = false; }

    public function mount(Pedido $pedido): void
    {
        $this->pedido     = $pedido->load('items.producto', 'historial');
        $this->estado     = $pedido->estado;
        $this->notas_admin = $pedido->notas_admin ?? '';
        $this->costo_envio = $pedido->costo_envio !== null ? (string) $pedido->costo_envio : '';
    }

    public function guardar(): void
    {
        $this->validate([
            'estado'      => 'required|in:pendiente,confirmado,preparando,enviado,listo_retiro,entregado,rechazado,cancelado',
            'notas_admin' => 'nullable|max:1000',
            'costo_envio' => 'nullable|numeric|min:0',
        ]);

        $estadoAnterior    = $this->pedido->estado;
        $cancelados        = ['rechazado', 'cancelado'];
        $entrandoCancelado = in_array($this->estado, $cancelados) && ! in_array($estadoAnterior, $cancelados);
        $saliendoCancelado = ! in_array($this->estado, $cancelados) && in_array($estadoAnterior, $cancelados);
        $cambioDeEstado    = $estadoAnterior !== $this->estado;

        $datos = [
            'estado'      => $this->estado,
            'notas_admin' => $this->notas_admin ?: null,
        ];

        if ($this->pedido->metodo_entrega === 'envio') {
            $costoEnvio = $this->costo_envio !== '' ? (float) $this->costo_envio : null;
            $datos['costo_envio'] = $costoEnvio;
            $datos['total'] = $this->pedido->subtotal - $this->pedido->monto_descuento + ($costoEnvio ?? 0);
        }

        DB::transaction(function () use ($datos, $entrandoCancelado, $saliendoCancelado, $estadoAnterior, $cambioDeEstado) {
            // Ajustar stock con lock para evitar race conditions
            if ($entrandoCancelado || $saliendoCancelado) {
                foreach ($this->pedido->items as $item) {
                    if ($item->tipo === 'madera' && $item->condimentos) {
                        foreach ($item->condimentos as $condimento) {
                            if (empty($condimento['producto_id'])) continue;
                            $producto = Producto::lockForUpdate()->find($condimento['producto_id']);
                            if (! $producto) continue;
                            if ($entrandoCancelado) {
                                $producto->increment('stock', $condimento['cantidad']);
                            } else {
                                if ($producto->stock < $condimento['cantidad']) {
                                    \Log::warning("Stock insuficiente al reactivar pedido {$this->pedido->numero_pedido}: producto #{$producto->id} tiene {$producto->stock}, se necesitan {$condimento['cantidad']}.");
                                }
                                $producto->decrement('stock', $condimento['cantidad']);
                            }
                        }
                    } elseif ($item->producto_id) {
                        $producto = Producto::lockForUpdate()->find($item->producto_id);
                        if (! $producto) continue;
                        if ($entrandoCancelado) {
                            $producto->increment('stock', $item->cantidad);
                        } else {
                            if ($producto->stock < $item->cantidad) {
                                \Log::warning("Stock insuficiente al reactivar pedido {$this->pedido->numero_pedido}: producto #{$producto->id} tiene {$producto->stock}, se necesitan {$item->cantidad}.");
                            }
                            $producto->decrement('stock', $item->cantidad);
                        }
                    }
                }
            }

            $this->pedido->update($datos);

            if ($cambioDeEstado) {
                PedidoHistorialEstado::create([
                    'pedido_id'       => $this->pedido->id,
                    'estado_anterior' => $estadoAnterior,
                    'estado_nuevo'    => $this->estado,
                    'notas'           => $this->notas_admin ?: null,
                ]);
            }
        });

        $this->pedido->refresh();

        if ($cambioDeEstado) {
            $this->pedido->load('historial');
        }

        $this->guardado = true;
    }

    public function render()
    {
        return view('livewire.admin.detalle-pedido')
            ->layout('layouts.admin', ['titulo' => 'Pedido ' . $this->pedido->numero_pedido . ' — Admin Tileo']);
    }
}
