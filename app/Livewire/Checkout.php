<?php

namespace App\Livewire;

use App\Mail\ConfirmacionClienteMail;
use App\Mail\NuevoPedidoMail;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Checkout extends Component
{
    #[Validate('required|min:2|max:100')]
    public string $nombre = '';

    #[Validate('required|email|max:150')]
    public string $email = '';

    #[Validate('required|min:8|max:30')]
    public string $telefono = '';

    #[Validate('required|in:envio,retiro')]
    public string $metodo_entrega = 'retiro';

    #[Validate('required|in:transferencia,efectivo')]
    public string $metodo_pago = 'efectivo';

    #[Validate('nullable|min:5|max:255')]
    public string $direccion = '';

    #[Validate('nullable|max:500')]
    public string $notas = '';

    public bool $procesando = false;

    public function obtenerItems(): array
    {
        return session('carrito', []);
    }

    public function obtenerSubtotal(): float
    {
        return collect($this->obtenerItems())->sum('subtotal');
    }

    public function confirmarPedido(): void
    {
        $this->validate();

        $items = $this->obtenerItems();

        if (empty($items)) {
            $this->addError('carrito', 'Tu carrito está vacío.');
            return;
        }

        // Validar que haya stock disponible
        foreach ($items as $item) {
            $producto = Producto::find($item['id']);
            if (! $producto || $producto->stock < $item['cantidad']) {
                $this->addError('carrito', "No hay stock suficiente para {$item['nombre']}.");
                return;
            }
        }

        $this->procesando = true;

        $subtotal = $this->obtenerSubtotal();

        // Crear el pedido
        $pedido = Pedido::create([
            'nombre_cliente'   => $this->nombre,
            'email_cliente'    => $this->email,
            'telefono_cliente' => $this->telefono,
            'metodo_entrega'   => $this->metodo_entrega,
            'metodo_pago'      => $this->metodo_pago,
            'direccion_envio'  => $this->metodo_entrega === 'envio' ? $this->direccion : null,
            'costo_envio'      => $this->metodo_entrega === 'envio' ? null : 0,
            'subtotal'         => $subtotal,
            'total'            => $subtotal, // el envío lo confirma el admin
            'estado'           => 'pendiente',
            'notas_cliente'    => $this->notas ?: null,
        ]);

        // Crear los items y descontar stock
        foreach ($items as $item) {
            PedidoItem::create([
                'pedido_id'      => $pedido->id,
                'producto_id'    => $item['id'],
                'nombre_producto' => $item['nombre'],
                'precio_unitario' => $item['precio'],
                'cantidad'       => $item['cantidad'],
                'subtotal'       => $item['subtotal'],
            ]);

            Producto::where('id', $item['id'])->decrement('stock', $item['cantidad']);
        }

        $pedidoConItems = $pedido->load('items');

        // Enviar email al admin
        $emailAdmin = config('tileo.email_admin');
        try {
            Mail::to($emailAdmin)->send(new NuevoPedidoMail($pedidoConItems));
        } catch (\Exception $e) {
            \Log::error('Error al enviar email al admin: ' . $e->getMessage());
        }

        // Enviar confirmación al cliente
        try {
            Mail::to($pedido->email_cliente)->send(new ConfirmacionClienteMail($pedidoConItems));
        } catch (\Exception $e) {
            \Log::error('Error al enviar email al cliente: ' . $e->getMessage());
        }

        // Limpiar carrito
        session()->forget('carrito');

        $this->redirect(route('confirmacion-pedido', $pedido->numero_pedido), navigate: true);
    }

    public function render()
    {
        return view('livewire.checkout', [
            'items'    => $this->obtenerItems(),
            'subtotal' => $this->obtenerSubtotal(),
        ])->layout('layouts.app', ['titulo' => 'Checkout — Tileo']);
    }
}
