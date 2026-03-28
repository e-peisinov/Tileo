<?php

namespace App\Livewire;

use App\Mail\ConfirmacionClienteMail;
use App\Mail\NuevoPedidoMail;
use App\Models\Configuracion;
use App\Models\CodigoDescuento;
use App\Models\Madera;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use App\Models\UsoCodigoDescuento;
use Illuminate\Support\Facades\DB;
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

    public function obtenerMaderas(): array
    {
        return session('carrito_maderas', []);
    }

    public function obtenerSubtotal(): float
    {
        $totalProductos = collect($this->obtenerItems())->sum('subtotal');
        $totalMaderas   = collect($this->obtenerMaderas())->sum('subtotal');
        return $totalProductos + $totalMaderas;
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

        if ($this->metodo_entrega === 'envio' && empty(trim($this->direccion))) {
            $this->addError('direccion', 'La dirección de envío es requerida.');
            return;
        }

        $items   = $this->obtenerItems();
        $maderas = $this->obtenerMaderas();

        if (empty($items) && empty($maderas)) {
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

        if ($this->metodo_entrega === 'envio' && empty(trim($this->direccion))) {
            $this->addError('direccion', 'La dirección de envío es requerida.');
            return;
        }

        $items   = $this->obtenerItems();
        $maderas = $this->obtenerMaderas();

        if (empty($items) && empty($maderas)) {
            $this->addError('carrito', 'Tu carrito está vacío.');
            return;
        }

        // Calcular demanda total de stock por producto (productos individuales + condimentos de maderas)
        $demanda = [];

        foreach ($items as $item) {
            $id = $item['id'];
            $demanda[$id] = ($demanda[$id] ?? 0) + $item['cantidad'];
        }

        foreach ($maderas as $madera) {
            foreach ($madera['condimentos'] as $condimento) {
                $id = $condimento['producto_id'];
                $demanda[$id] = ($demanda[$id] ?? 0) + $condimento['cantidad'];
            }
        }

        $this->procesando = true;

        $subtotal         = $this->obtenerSubtotal();
        $total            = $this->obtenerTotal();
        $emailNormalizado = strtolower(trim($this->email));

        // Re-validar el código de descuento con el email real del cliente
        if ($this->descuentoAplicado) {
            $codigoObj = CodigoDescuento::find($this->descuentoAplicado->id);
            if (! $codigoObj || ! $codigoObj->estaVigente()) {
                $this->addError('codigoDescuentoInput', 'El código de descuento ya no es válido.');
                $this->quitarDescuento();
                $this->procesando = false;
                return;
            }
            if ($codigoObj->solo_un_uso_por_email && $codigoObj->yaUsadoPorEmail($emailNormalizado)) {
                $this->addError('codigoDescuentoInput', 'Ya usaste este código con este email.');
                $this->quitarDescuento();
                $this->procesando = false;
                return;
            }
        }

        $descuentoAplicadoId = $this->descuentoAplicado?->id;
        $montoDescuento      = $this->montoDescuento;

        try {
            $pedido = DB::transaction(function () use (
                $items, $maderas, $subtotal, $total, $demanda,
                $emailNormalizado, $descuentoAplicadoId, $montoDescuento
            ) {
                // Verificar y bloquear stock dentro de la transacción para evitar race conditions
                $productosLocked = [];
                foreach ($demanda as $productoId => $cantidadNecesaria) {
                    $producto = Producto::lockForUpdate()->find($productoId);
                    if (! $producto || $producto->stock < $cantidadNecesaria) {
                        $nombre = $producto?->nombre ?? "Producto #$productoId";
                        throw new \Exception("No hay stock suficiente para {$nombre}.");
                    }
                    $productosLocked[$productoId] = $producto;
                }

                $pedido = Pedido::create([
                    'nombre_cliente'      => $this->nombre,
                    'email_cliente'       => $emailNormalizado,
                    'telefono_cliente'    => $this->telefono,
                    'metodo_entrega'      => $this->metodo_entrega,
                    'metodo_pago'         => $this->metodo_pago,
                    'direccion_envio'     => $this->metodo_entrega === 'envio' ? $this->direccion : null,
                    'costo_envio'         => $this->metodo_entrega === 'envio' ? null : 0,
                    'subtotal'            => $subtotal,
                    'monto_descuento'     => $montoDescuento,
                    'total'               => $total,
                    'estado'              => 'pendiente',
                    'notas_cliente'       => $this->notas ?: null,
                    'codigo_descuento_id' => $descuentoAplicadoId,
                ]);

                // Crear items de productos individuales
                foreach ($items as $item) {
                    PedidoItem::create([
                        'pedido_id'       => $pedido->id,
                        'producto_id'     => $item['id'],
                        'nombre_producto' => $item['nombre'],
                        'precio_unitario' => $item['precio'],
                        'cantidad'        => $item['cantidad'],
                        'subtotal'        => $item['subtotal'],
                        'tipo'            => 'producto',
                    ]);
                }

                // Crear items de maderas
                foreach ($maderas as $madera) {
                    PedidoItem::create([
                        'pedido_id'       => $pedido->id,
                        'producto_id'     => null,
                        'nombre_producto' => $madera['nombre'],
                        'precio_unitario' => $madera['precio'],
                        'cantidad'        => 1,
                        'subtotal'        => $madera['subtotal'],
                        'tipo'            => 'madera',
                        'madera_id'       => $madera['madera_id'],
                        'condimentos'     => $madera['condimentos'],
                    ]);
                }

                // Descontar stock usando los productos ya bloqueados (dispara el Observer)
                foreach ($demanda as $productoId => $cantidadNecesaria) {
                    $producto = $productosLocked[$productoId];
                    $producto->stock = max(0, $producto->stock - $cantidadNecesaria);
                    $producto->save();
                }

                // Registrar uso del código de descuento
                if ($descuentoAplicadoId) {
                    UsoCodigoDescuento::create([
                        'codigo_descuento_id' => $descuentoAplicadoId,
                        'pedido_id'           => $pedido->id,
                        'email_cliente'       => $emailNormalizado,
                        'monto_descontado'    => $montoDescuento,
                    ]);
                    CodigoDescuento::where('id', $descuentoAplicadoId)->increment('usos_actuales');
                }

                return $pedido;
            });
        } catch (\Exception $e) {
            $this->addError('carrito', $e->getMessage());
            $this->procesando = false;
            return;
        }

        // Refrescar para asegurar que numero_pedido (generado en el evento created) esté disponible
        $pedido->refresh();

        $pedidoConItems = $pedido->load('items');

        // Enviar emails (queue: con driver sync se envía igual; con database/redis se vuelve asíncrono)
        $emailAdmin = config('tileo.email_admin');
        try {
            Mail::to($emailAdmin)->queue(new NuevoPedidoMail($pedidoConItems));
        } catch (\Exception $e) {
            \Log::error('Error al encolar email al admin: ' . $e->getMessage());
        }

        try {
            Mail::to($pedido->email_cliente)->queue(new ConfirmacionClienteMail($pedidoConItems));
        } catch (\Exception $e) {
            \Log::error('Error al encolar email al cliente: ' . $e->getMessage());
        }

        // Limpiar carrito
        session()->forget('carrito');
        session()->forget('carrito_maderas');

        $this->redirect(route('confirmacion-pedido', $pedido->numero_pedido), navigate: true);
    }

    public function render()
    {
        return view('livewire.checkout', [
            'items'          => $this->obtenerItems(),
            'maderas'        => $this->obtenerMaderas(),
            'subtotal'       => $this->obtenerSubtotal(),
            'total'          => $this->obtenerTotal(),
            'modoVacaciones' => Configuracion::obtener('modo_vacaciones', false),
            'msgVacaciones'  => Configuracion::obtener('mensaje_vacaciones', ''),
            'tiempoEntrega'  => Configuracion::obtener('tiempo_entrega', ''),
            'cbu'            => Configuracion::obtener('cbu', ''),
            'aliasCbu'       => Configuracion::obtener('alias_cbu', ''),
            'titularCuenta'  => Configuracion::obtener('titular_cuenta', ''),
        ])->layout('layouts.app', [
            'titulo'      => 'Checkout — Tileo',
            'descripcion' => 'Completá tu pedido de especias artesanales Tileo.',
        ]);
    }
}
