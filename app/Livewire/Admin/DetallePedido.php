<?php

namespace App\Livewire\Admin;

use App\Models\Pedido;
use App\Models\Producto;
use Livewire\Attributes\Validate;
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
        $this->pedido     = $pedido->load('items.producto');
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

        $estadoAnterior = $this->pedido->estado;

        // Si se rechaza el pedido, reponer stock
        if (in_array($this->estado, ['rechazado', 'cancelado']) &&
            ! in_array($estadoAnterior, ['rechazado', 'cancelado'])) {
            foreach ($this->pedido->items as $item) {
                if ($item->producto_id) {
                    Producto::where('id', $item->producto_id)->increment('stock', $item->cantidad);
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
            $datos['total'] = $this->pedido->subtotal + ($costoEnvio ?? 0);
        }

        $this->pedido->update($datos);
        $this->pedido->refresh();

        $this->guardado = true;
    }

    public function render()
    {
        return view('livewire.admin.detalle-pedido')
            ->layout('layouts.app', ['titulo' => 'Pedido ' . $this->pedido->numero_pedido . ' — Admin Tileo']);
    }
}
