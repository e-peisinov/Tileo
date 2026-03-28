<?php

namespace App\Livewire\Admin;

use App\Mail\CambioEstadoMail;
use App\Models\Pedido;
use App\Models\PedidoHistorialEstado;
use App\Models\Producto;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class DetallePedido extends Component
{
    public Pedido $pedido;

    public string $estado = '';
    public string $notas_admin = '';
    public string $costo_envio = '';
    public bool $guardado = false;

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

        $estadoAnterior   = $this->pedido->estado;
        $cancelados       = ['rechazado', 'cancelado'];
        $entrandoCancelado = in_array($this->estado, $cancelados) && ! in_array($estadoAnterior, $cancelados);
        $saliendoCancelado = ! in_array($this->estado, $cancelados) && in_array($estadoAnterior, $cancelados);

        // Reponer stock al cancelar/rechazar; descontar si se reactiva desde cancelado
        if ($entrandoCancelado || $saliendoCancelado) {
            foreach ($this->pedido->items as $item) {
                if ($item->producto_id) {
                    if ($entrandoCancelado) {
                        Producto::where('id', $item->producto_id)->increment('stock', $item->cantidad);
                    } else {
                        Producto::where('id', $item->producto_id)->decrement('stock', $item->cantidad);
                    }
                }
            }
        }

        $datos = [
            'estado'      => $this->estado,
            'notas_admin' => $this->notas_admin ?: null,
        ];

        if ($this->pedido->metodo_entrega === 'envio') {
            $costoEnvio = $this->costo_envio !== '' ? (float) $this->costo_envio : null;
            $datos['costo_envio'] = $costoEnvio;
            $datos['total'] = $this->pedido->subtotal - $this->pedido->monto_descuento + ($costoEnvio ?? 0);
        }

        $this->pedido->update($datos);
        $this->pedido->refresh();

        if ($estadoAnterior !== $this->estado) {
            PedidoHistorialEstado::create([
                'pedido_id'       => $this->pedido->id,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo'    => $this->estado,
                'notas'           => $this->notas_admin ?: null,
            ]);

            if ($this->pedido->email_cliente) {
                try {
                    Mail::to($this->pedido->email_cliente)
                        ->queue(new CambioEstadoMail($this->pedido, $estadoAnterior));
                } catch (\Exception $e) {
                    \Log::error('Error al encolar email cambio estado: ' . $e->getMessage());
                }
            }

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
