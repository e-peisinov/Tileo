<?php

namespace App\Livewire;

use App\Mail\ConfirmacionClienteMail;
use App\Mail\NuevoPedidoMail;
use App\Models\Configuracion;
use App\Models\CodigoDescuento;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use App\Models\UsoCodigoDescuento;
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

    // Código de descuento
    public string $codigoDescuentoInput = '';
    public ?CodigoDescuento $descuentoAplicado = null;
    public float $montoDescuento = 0;
    public string $mensajeDescuento = '';
    public bool $descuentoExitoso = false;

    public bool $procesando = false;
    public bool $revisando  = false;

    public function obtenerItems(): array
    {
        return session('carrito', []);
    }

    public function obtenerSubtotal(): float
    {
        return collect($this->obtenerItems())->sum('subtotal');
    }

    public function obtenerTotal(): float
    {
        return max(0, $this->obtenerSubtotal() - $this->montoDescuento);
    }

    public function aplicarDescuento(): void
    {
        $this->mensajeDescuento   = '';
        $this->descuentoExitoso   = false;
        $this->descuentoAplicado  = null;
        $this->montoDescuento     = 0;

        $codigo = strtoupper(trim($this->codigoDescuentoInput));

        if (empty($codigo)) {
            $this->mensajeDescuento = 'Ingresá un código de descuento.';
            return;
        }

        $codigoObj = CodigoDescuento::where('codigo', $codigo)->first();

        if (! $codigoObj || ! $codigoObj->estaVigente()) {
            $this->mensajeDescuento = 'El código no es válido o ya expiró.';
            return;
        }

        $subtotal = $this->obtenerSubtotal();

        if ($codigoObj->minimo_compra && $subtotal < $codigoObj->minimo_compra) {
            $this->mensajeDescuento = 'El pedido mínimo para usar este código es $' . number_format($codigoObj->minimo_compra, 2, ',', '.');
            return;
        }

        if ($codigoObj->solo_un_uso_por_email && ! empty($this->email) && $codigoObj->yaUsadoPorEmail($this->email)) {
            $this->mensajeDescuento = 'Ya usaste este código anteriormente.';
            return;
        }

        $this->descuentoAplicado = $codigoObj;
        $this->montoDescuento    = $codigoObj->calcularDescuento($subtotal);
        $this->descuentoExitoso  = true;

        $descripcion = $codigoObj->tipo === 'porcentaje'
            ? $codigoObj->valor . '% de descuento'
            : '$' . number_format($codigoObj->valor, 2, ',', '.') . ' de descuento';

        $this->mensajeDescuento = "¡Código aplicado! {$descripcion}.";
    }

    public function quitarDescuento(): void
    {
        $this->codigoDescuentoInput = '';
        $this->descuentoAplicado    = null;
        $this->montoDescuento       = 0;
        $this->mensajeDescuento     = '';
        $this->descuentoExitoso     = false;
    }

    public function revisarPedido(): void
    {
        $this->validate();

        $items = $this->obtenerItems();
        if (empty($items)) {
            $this->addError('carrito', 'Tu carrito está vacío.');
            return;
        }

        $this->revisando = true;
    }

    public function volverFormulario(): void
    {
        $this->revisando = false;
    }

    public function confirmarPedido(): void
    {
        // Verificar modo vacaciones
        if (Configuracion::obtener('modo_vacaciones', false)) {
            $this->addError('carrito', Configuracion::obtener('mensaje_vacaciones', 'Temporalmente no estamos recibiendo pedidos.'));
            $this->revisando = false;
            return;
        }

        $this->validate();

        $items = $this->obtenerItems();

        if (empty($items)) {
            $this->addError('carrito', 'Tu carrito está vacío.');
            return;
        }

        // Validar stock disponible
        foreach ($items as $item) {
            $producto = Producto::find($item['id']);
            if (! $producto || $producto->stock < $item['cantidad']) {
                $this->addError('carrito', "No hay stock suficiente para {$item['nombre']}.");
                return;
            }
        }

        $this->procesando = true;

        $subtotal = $this->obtenerSubtotal();
        $total    = $this->obtenerTotal();

        // Crear el pedido
        $pedido = Pedido::create([
            'nombre_cliente'      => $this->nombre,
            'email_cliente'       => $this->email,
            'telefono_cliente'    => $this->telefono,
            'metodo_entrega'      => $this->metodo_entrega,
            'metodo_pago'         => $this->metodo_pago,
            'direccion_envio'     => $this->metodo_entrega === 'envio' ? $this->direccion : null,
            'costo_envio'         => $this->metodo_entrega === 'envio' ? null : 0,
            'subtotal'            => $subtotal,
            'monto_descuento'     => $this->montoDescuento,
            'total'               => $total,
            'estado'              => 'pendiente',
            'notas_cliente'       => $this->notas ?: null,
            'codigo_descuento_id' => $this->descuentoAplicado?->id,
        ]);

        // Crear los items y descontar stock (usando save() para disparar el Observer)
        foreach ($items as $item) {
            PedidoItem::create([
                'pedido_id'       => $pedido->id,
                'producto_id'     => $item['id'],
                'nombre_producto' => $item['nombre'],
                'precio_unitario' => $item['precio'],
                'cantidad'        => $item['cantidad'],
                'subtotal'        => $item['subtotal'],
            ]);

            // Usamos save() en lugar de decrement() para que el ProductoObserver se dispare
            $producto = Producto::find($item['id']);
            if ($producto) {
                $producto->stock = max(0, $producto->stock - $item['cantidad']);
                $producto->save();
            }
        }

        // Registrar uso del código de descuento
        if ($this->descuentoAplicado) {
            UsoCodigoDescuento::create([
                'codigo_descuento_id' => $this->descuentoAplicado->id,
                'pedido_id'           => $pedido->id,
                'email_cliente'       => $this->email,
                'monto_descontado'    => $this->montoDescuento,
            ]);

            $this->descuentoAplicado->increment('usos_actuales');
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
            'items'          => $this->obtenerItems(),
            'subtotal'       => $this->obtenerSubtotal(),
            'total'          => $this->obtenerTotal(),
            'modoVacaciones' => Configuracion::obtener('modo_vacaciones', false),
            'msgVacaciones'  => Configuracion::obtener('mensaje_vacaciones', ''),
            'tiempoEntrega'  => Configuracion::obtener('tiempo_entrega', ''),
            'cbu'            => Configuracion::obtener('cbu', ''),
            'aliasCbu'       => Configuracion::obtener('alias_cbu', ''),
            'titularCuenta'  => Configuracion::obtener('titular_cuenta', ''),
        ])->layout('layouts.app', ['titulo' => 'Checkout — Tileo']);
    }
}
