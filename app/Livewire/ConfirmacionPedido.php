<?php

namespace App\Livewire;

use App\Models\Configuracion;
use App\Models\Pedido;
use Livewire\Component;

class ConfirmacionPedido extends Component
{
    public string $numero;
    public ?Pedido $pedido = null;

    public function mount(string $numero): void
    {
        $this->numero = $numero;
        $this->pedido = Pedido::with('items')
            ->where('numero_pedido', $numero)
            ->firstOrFail();
    }

    public function generarMensajeWhatsApp(): string
    {
        $pedido = $this->pedido;
        $lineas = ["🌿 *Nuevo pedido Tileo — {$pedido->numero_pedido}*", ''];

        $lineas[] = "👤 *Cliente:* {$pedido->nombre_cliente}";
        $lineas[] = "📧 {$pedido->email_cliente}";
        $lineas[] = "📱 {$pedido->telefono_cliente}";
        $lineas[] = '';

        $entrega = $pedido->metodo_entrega === 'envio'
            ? "Envío a: {$pedido->direccion_envio}"
            : 'Retiro en local';
        $lineas[] = "🚚 *Entrega:* {$entrega}";

        $pago = $pedido->metodo_pago === 'transferencia' ? 'Transferencia bancaria' : 'Efectivo';
        $lineas[] = "💳 *Pago:* {$pago}";
        $lineas[] = '';

        $lineas[] = '📦 *Productos:*';
        foreach ($pedido->items as $item) {
            $lineas[] = "  • {$item['nombre_producto']} x{$item['cantidad']} — $" . number_format($item['subtotal'], 2, ',', '.');
        }
        $lineas[] = '';
        $lineas[] = '💰 *Subtotal:* $' . number_format($pedido->subtotal, 2, ',', '.');

        if ($pedido->monto_descuento > 0) {
            $lineas[] = '🏷️ *Descuento:* − $' . number_format($pedido->monto_descuento, 2, ',', '.');
            $lineas[] = '✅ *Total:* $' . number_format($pedido->total, 2, ',', '.');
        }

        if ($pedido->notas_cliente) {
            $lineas[] = '';
            $lineas[] = "📝 *Notas:* {$pedido->notas_cliente}";
        }

        return urlencode(implode("\n", $lineas));
    }

    public function render()
    {
        return view('livewire.confirmacion-pedido', [
            'mensajeWa'     => $this->generarMensajeWhatsApp(),
            'tiempoEntrega' => Configuracion::obtener('tiempo_entrega', ''),
            'cbu'           => Configuracion::obtener('cbu', ''),
            'aliasCbu'      => Configuracion::obtener('alias_cbu', ''),
            'titularCuenta' => Configuracion::obtener('titular_cuenta', ''),
        ])->layout('layouts.app', ['titulo' => 'Pedido confirmado — Tileo']);
    }
}
