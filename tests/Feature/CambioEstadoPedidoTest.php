<?php

namespace Tests\Feature;

use App\Mail\CambioEstadoMail;
use App\Models\Categoria;
use App\Models\Pedido;
use App\Models\PedidoHistorialEstado;
use App\Models\PedidoItem;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class CambioEstadoPedidoTest extends TestCase
{
    use RefreshDatabase;

    private function crearAdmin(): User
    {
        return User::factory()->create(['es_admin' => true]);
    }

    private function crearProducto(int $stock = 10): Producto
    {
        $categoria = Categoria::create([
            'nombre'      => 'Especias',
            'descripcion' => 'Especias',
            'activo'      => true,
        ]);

        return Producto::create([
            'nombre'       => 'Pimentón',
            'descripcion'  => 'Pimentón',
            'precio'       => 1500.00,
            'stock'        => $stock,
            'unidad'       => 'tubo',
            'activo'       => true,
            'destacado'    => false,
            'categoria_id' => $categoria->id,
        ]);
    }

    private function crearPedidoConItem(Producto $producto, int $cantidad = 2, string $estado = 'pendiente'): Pedido
    {
        $pedido = Pedido::create([
            'nombre_cliente'   => 'Ana García',
            'email_cliente'    => 'ana@example.com',
            'telefono_cliente' => '2324123456',
            'metodo_entrega'   => 'retiro',
            'metodo_pago'      => 'efectivo',
            'subtotal'         => $producto->precio * $cantidad,
            'total'            => $producto->precio * $cantidad,
            'estado'           => $estado,
        ]);

        PedidoItem::create([
            'pedido_id'       => $pedido->id,
            'producto_id'     => $producto->id,
            'nombre_producto' => $producto->nombre,
            'precio_unitario' => $producto->precio,
            'cantidad'        => $cantidad,
            'subtotal'        => $producto->precio * $cantidad,
        ]);

        return $pedido;
    }

    // ── Cambio de estado ────────────────────────────────────────────────────

    public function test_admin_puede_cambiar_estado_de_pedido(): void
    {
        Mail::fake();

        $admin   = $this->crearAdmin();
        $producto = $this->crearProducto();
        $pedido  = $this->crearPedidoConItem($producto);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\Admin\DetallePedido::class, ['pedido' => $pedido])
            ->set('estado', 'confirmado')
            ->call('guardar');

        $this->assertEquals('confirmado', $pedido->fresh()->estado);
    }

    public function test_cambio_de_estado_registra_historial(): void
    {
        Mail::fake();

        $admin   = $this->crearAdmin();
        $producto = $this->crearProducto();
        $pedido  = $this->crearPedidoConItem($producto);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\Admin\DetallePedido::class, ['pedido' => $pedido])
            ->set('estado', 'confirmado')
            ->call('guardar');

        $this->assertDatabaseHas('pedido_historial_estados', [
            'pedido_id'       => $pedido->id,
            'estado_anterior' => 'pendiente',
            'estado_nuevo'    => 'confirmado',
        ]);
    }

    public function test_cambio_de_estado_envia_email_al_cliente(): void
    {
        Mail::fake();

        $admin   = $this->crearAdmin();
        $producto = $this->crearProducto();
        $pedido  = $this->crearPedidoConItem($producto);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\Admin\DetallePedido::class, ['pedido' => $pedido])
            ->set('estado', 'confirmado')
            ->call('guardar');

        Mail::assertSent(CambioEstadoMail::class, fn ($mail) =>
            $mail->hasTo('ana@example.com')
        );
    }

    public function test_sin_cambio_de_estado_no_envia_email(): void
    {
        Mail::fake();

        $admin   = $this->crearAdmin();
        $producto = $this->crearProducto();
        $pedido  = $this->crearPedidoConItem($producto);

        $this->actingAs($admin);

        // Guardar sin cambiar el estado (sigue en 'pendiente')
        Livewire::test(\App\Livewire\Admin\DetallePedido::class, ['pedido' => $pedido])
            ->set('estado', 'pendiente')
            ->set('notas_admin', 'Anotación interna')
            ->call('guardar');

        Mail::assertNotSent(CambioEstadoMail::class);
    }

    // ── Stock al cancelar/rechazar ──────────────────────────────────────────

    public function test_cancelar_pedido_repone_stock(): void
    {
        Mail::fake();

        $admin    = $this->crearAdmin();
        $producto = $this->crearProducto(10);
        $pedido   = $this->crearPedidoConItem($producto, 3);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\Admin\DetallePedido::class, ['pedido' => $pedido])
            ->set('estado', 'cancelado')
            ->call('guardar');

        $this->assertEquals(13, $producto->fresh()->stock); // 10 + 3 repuesto
    }

    public function test_rechazar_pedido_repone_stock(): void
    {
        Mail::fake();

        $admin    = $this->crearAdmin();
        $producto = $this->crearProducto(10);
        $pedido   = $this->crearPedidoConItem($producto, 2);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\Admin\DetallePedido::class, ['pedido' => $pedido])
            ->set('estado', 'rechazado')
            ->call('guardar');

        $this->assertEquals(12, $producto->fresh()->stock); // 10 + 2 repuesto
    }

    public function test_reactivar_pedido_cancelado_descuenta_stock(): void
    {
        Mail::fake();

        $admin    = $this->crearAdmin();
        $producto = $this->crearProducto(10);
        $pedido   = $this->crearPedidoConItem($producto, 2, 'cancelado');

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\Admin\DetallePedido::class, ['pedido' => $pedido])
            ->set('estado', 'confirmado')
            ->call('guardar');

        $this->assertEquals(8, $producto->fresh()->stock); // 10 - 2 descontado
    }
}
