<?php

namespace Tests\Feature;

use App\Mail\ConfirmacionClienteMail;
use App\Mail\NuevoPedidoMail;
use App\Models\Categoria;
use App\Models\Configuracion;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function crearProducto(array $atributos = []): Producto
    {
        $categoria = Categoria::create([
            'nombre'      => 'Especias',
            'descripcion' => 'Especias varias',
            'activo'      => true,
        ]);

        return Producto::create(array_merge([
            'nombre'       => 'Pimentón',
            'descripcion'  => 'Pimentón dulce artesanal',
            'precio'       => 1500.00,
            'stock'        => 10,
            'unidad'       => 'tubo',
            'activo'       => true,
            'destacado'    => false,
            'categoria_id' => $categoria->id,
        ], $atributos));
    }

    private function carritoConProducto(Producto $producto, int $cantidad = 2): array
    {
        return [
            $producto->id => [
                'id'       => $producto->id,
                'nombre'   => $producto->nombre,
                'precio'   => (float) $producto->precio,
                'cantidad' => $cantidad,
                'subtotal' => (float) $producto->precio * $cantidad,
                'imagen'   => null,
            ],
        ];
    }

    // ── Flujo completo ──────────────────────────────────────────────────────

    public function test_checkout_crea_pedido_correctamente(): void
    {
        Mail::fake();

        $producto = $this->crearProducto();
        $carrito  = $this->carritoConProducto($producto);

        session()->put('carrito', $carrito);

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('nombre', 'Ana García')
            ->set('email', 'ana@example.com')
            ->set('telefono', '2324123456')
            ->set('metodo_entrega', 'retiro')
            ->set('metodo_pago', 'efectivo')
            ->call('confirmarPedido');

        $this->assertDatabaseHas('pedidos', [
            'nombre_cliente'   => 'Ana García',
            'email_cliente'    => 'ana@example.com',
            'telefono_cliente' => '2324123456',
            'metodo_entrega'   => 'retiro',
            'metodo_pago'      => 'efectivo',
            'estado'           => 'pendiente',
        ]);
    }

    public function test_checkout_descuenta_stock_al_confirmar(): void
    {
        Mail::fake();

        $producto = $this->crearProducto(['stock' => 10]);
        $carrito  = $this->carritoConProducto($producto, 3);

        session()->put('carrito', $carrito);

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('nombre', 'Ana García')
            ->set('email', 'ana@example.com')
            ->set('telefono', '2324123456')
            ->set('metodo_entrega', 'retiro')
            ->set('metodo_pago', 'efectivo')
            ->call('confirmarPedido');

        $this->assertEquals(7, $producto->fresh()->stock);
    }

    public function test_checkout_envia_emails_al_confirmar(): void
    {
        Mail::fake();

        $producto = $this->crearProducto();
        $carrito  = $this->carritoConProducto($producto);

        session()->put('carrito', $carrito);

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('nombre', 'Ana García')
            ->set('email', 'ana@example.com')
            ->set('telefono', '2324123456')
            ->set('metodo_entrega', 'retiro')
            ->set('metodo_pago', 'efectivo')
            ->call('confirmarPedido');

        Mail::assertSent(NuevoPedidoMail::class);
        Mail::assertSent(ConfirmacionClienteMail::class, fn ($mail) =>
            $mail->hasTo('ana@example.com')
        );
    }

    public function test_checkout_vacia_el_carrito_al_confirmar(): void
    {
        Mail::fake();

        $producto = $this->crearProducto();
        $carrito  = $this->carritoConProducto($producto);

        session()->put('carrito', $carrito);

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('nombre', 'Ana García')
            ->set('email', 'ana@example.com')
            ->set('telefono', '2324123456')
            ->set('metodo_entrega', 'retiro')
            ->set('metodo_pago', 'efectivo')
            ->call('confirmarPedido');

        $this->assertNull(session('carrito'));
    }

    public function test_checkout_genera_numero_pedido_con_prefijo_til(): void
    {
        Mail::fake();

        $producto = $this->crearProducto();
        $carrito  = $this->carritoConProducto($producto);

        session()->put('carrito', $carrito);

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('nombre', 'Ana García')
            ->set('email', 'ana@example.com')
            ->set('telefono', '2324123456')
            ->set('metodo_entrega', 'retiro')
            ->set('metodo_pago', 'efectivo')
            ->call('confirmarPedido');

        $pedido = Pedido::first();
        $this->assertStringStartsWith('TIL-', $pedido->numero_pedido);
    }

    public function test_checkout_rechaza_carrito_vacio(): void
    {
        session()->forget('carrito');

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('nombre', 'Ana García')
            ->set('email', 'ana@example.com')
            ->set('telefono', '2324123456')
            ->set('metodo_entrega', 'retiro')
            ->set('metodo_pago', 'efectivo')
            ->call('confirmarPedido')
            ->assertHasErrors('carrito');
    }

    public function test_checkout_rechaza_si_no_hay_stock_suficiente(): void
    {
        $producto = $this->crearProducto(['stock' => 1]);
        $carrito  = $this->carritoConProducto($producto, 5); // pide 5, hay 1

        session()->put('carrito', $carrito);

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('nombre', 'Ana García')
            ->set('email', 'ana@example.com')
            ->set('telefono', '2324123456')
            ->set('metodo_entrega', 'retiro')
            ->set('metodo_pago', 'efectivo')
            ->call('confirmarPedido')
            ->assertHasErrors('carrito');
    }

}
