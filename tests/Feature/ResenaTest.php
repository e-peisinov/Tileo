<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use App\Models\Resena;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ResenaTest extends TestCase
{
    use RefreshDatabase;

    private function crearProducto(): Producto
    {
        $categoria = Categoria::create([
            'nombre'      => 'Especias',
            'descripcion' => 'Especias',
            'activo'      => true,
        ]);

        return Producto::create([
            'nombre'       => 'Pimentón',
            'descripcion'  => 'Pimentón dulce artesanal',
            'precio'       => 1500.00,
            'stock'        => 10,
            'unidad'       => 'tubo',
            'activo'       => true,
            'destacado'    => false,
            'categoria_id' => $categoria->id,
        ]);
    }

    private function crearPedidoConItem(Producto $producto, string $estado = 'entregado'): Pedido
    {
        $pedido = Pedido::create([
            'nombre_cliente'   => 'Ana García',
            'email_cliente'    => 'ana@example.com',
            'telefono_cliente' => '2324123456',
            'metodo_entrega'   => 'retiro',
            'metodo_pago'      => 'efectivo',
            'subtotal'         => $producto->precio,
            'total'            => $producto->precio,
            'estado'           => $estado,
        ]);

        PedidoItem::create([
            'pedido_id'       => $pedido->id,
            'producto_id'     => $producto->id,
            'nombre_producto' => $producto->nombre,
            'precio_unitario' => $producto->precio,
            'cantidad'        => 1,
            'subtotal'        => $producto->precio,
        ]);

        return $pedido;
    }

    // ── Verificación de pedido ──────────────────────────────────────────────

    public function test_verificar_pedido_valido_habilita_el_formulario(): void
    {
        $producto = $this->crearProducto();
        $pedido   = $this->crearPedidoConItem($producto);

        Livewire::test(\App\Livewire\FormularioResena::class, ['productoId' => $producto->id])
            ->set('numeroPedido', $pedido->numero_pedido)
            ->call('verificarPedido')
            ->assertSet('pedidoVerificado', $pedido->id)
            ->assertSet('errorPedido', '');
    }

    public function test_pedido_inexistente_no_habilita_el_formulario(): void
    {
        $producto = $this->crearProducto();

        Livewire::test(\App\Livewire\FormularioResena::class, ['productoId' => $producto->id])
            ->set('numeroPedido', 'TIL-9999')
            ->call('verificarPedido')
            ->assertSet('pedidoVerificado', null);
    }

    public function test_pedido_sin_el_producto_no_habilita_el_formulario(): void
    {
        $categoria = Categoria::create(['nombre' => 'Condimentos', 'descripcion' => '', 'activo' => true]);

        $producto1 = $this->crearProducto();

        $producto2 = Producto::create([
            'nombre'       => 'Comino',
            'descripcion'  => 'Comino',
            'precio'       => 800.00,
            'stock'        => 5,
            'unidad'       => 'tubo',
            'activo'       => true,
            'destacado'    => false,
            'categoria_id' => $categoria->id,
        ]);

        // Pedido que incluye producto2, NO producto1
        $pedido = Pedido::create([
            'nombre_cliente'   => 'Carlos',
            'email_cliente'    => 'carlos@example.com',
            'telefono_cliente' => '2324000111',
            'metodo_entrega'   => 'retiro',
            'metodo_pago'      => 'efectivo',
            'subtotal'         => $producto2->precio,
            'total'            => $producto2->precio,
            'estado'           => 'entregado',
        ]);

        PedidoItem::create([
            'pedido_id'       => $pedido->id,
            'producto_id'     => $producto2->id,
            'nombre_producto' => $producto2->nombre,
            'precio_unitario' => $producto2->precio,
            'cantidad'        => 1,
            'subtotal'        => $producto2->precio,
        ]);

        Livewire::test(\App\Livewire\FormularioResena::class, ['productoId' => $producto1->id])
            ->set('numeroPedido', $pedido->numero_pedido)
            ->call('verificarPedido')
            ->assertSet('pedidoVerificado', null);
    }

    // ── Envío de reseña ─────────────────────────────────────────────────────

    public function test_crear_resena_la_guarda_en_db_como_no_aprobada(): void
    {
        $producto = $this->crearProducto();
        $pedido   = $this->crearPedidoConItem($producto);

        Livewire::test(\App\Livewire\FormularioResena::class, ['productoId' => $producto->id])
            ->set('numeroPedido', $pedido->numero_pedido)
            ->call('verificarPedido')
            ->set('calificacion', 5)
            ->set('comentario', 'Excelente producto, muy aromático.')
            ->set('nombreCliente', 'Ana García')
            ->call('enviar');

        $this->assertDatabaseHas('resenas', [
            'producto_id'  => $producto->id,
            'pedido_id'    => $pedido->id,
            'calificacion' => 5,
            'aprobada'     => false,
        ]);
    }

    public function test_crear_resena_marca_como_enviado(): void
    {
        $producto = $this->crearProducto();
        $pedido   = $this->crearPedidoConItem($producto);

        Livewire::test(\App\Livewire\FormularioResena::class, ['productoId' => $producto->id])
            ->set('numeroPedido', $pedido->numero_pedido)
            ->call('verificarPedido')
            ->set('calificacion', 4)
            ->set('nombreCliente', 'Ana García')
            ->call('enviar')
            ->assertSet('enviado', true);
    }

    public function test_no_se_puede_resenar_dos_veces_el_mismo_producto(): void
    {
        $producto = $this->crearProducto();
        $pedido   = $this->crearPedidoConItem($producto);

        Resena::create([
            'producto_id'    => $producto->id,
            'pedido_id'      => $pedido->id,
            'calificacion'   => 4,
            'nombre_cliente' => 'Ana García',
            'email_cliente'  => 'ana@example.com',
            'aprobada'       => false,
        ]);

        Livewire::test(\App\Livewire\FormularioResena::class, ['productoId' => $producto->id])
            ->set('numeroPedido', $pedido->numero_pedido)
            ->call('verificarPedido')
            ->assertSet('pedidoVerificado', null);

        $this->assertDatabaseCount('resenas', 1);
    }

    public function test_reseña_sin_calificacion_devuelve_error(): void
    {
        $producto = $this->crearProducto();
        $pedido   = $this->crearPedidoConItem($producto);

        Livewire::test(\App\Livewire\FormularioResena::class, ['productoId' => $producto->id])
            ->set('numeroPedido', $pedido->numero_pedido)
            ->call('verificarPedido')
            ->set('calificacion', 0)
            ->set('nombreCliente', 'Ana García')
            ->call('enviar')
            ->assertHasErrors('calificacion');
    }

    public function test_reseña_sin_pedido_verificado_devuelve_error(): void
    {
        $producto = $this->crearProducto();

        Livewire::test(\App\Livewire\FormularioResena::class, ['productoId' => $producto->id])
            ->set('calificacion', 5)
            ->set('nombreCliente', 'Ana García')
            ->call('enviar')
            ->assertSet('enviado', false);
    }

    // ── Moderación admin ────────────────────────────────────────────────────

    public function test_admin_puede_aprobar_resena(): void
    {
        $producto = $this->crearProducto();
        $pedido   = $this->crearPedidoConItem($producto);

        $resena = Resena::create([
            'producto_id'    => $producto->id,
            'pedido_id'      => $pedido->id,
            'calificacion'   => 5,
            'nombre_cliente' => 'Ana García',
            'email_cliente'  => 'ana@example.com',
            'aprobada'       => false,
        ]);

        $admin = \App\Models\User::factory()->create(['es_admin' => true]);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\Admin\GestionResenas::class)
            ->call('aprobar', $resena->id);

        $this->assertTrue($resena->fresh()->aprobada);
    }

    public function test_admin_puede_rechazar_resena(): void
    {
        $producto = $this->crearProducto();
        $pedido   = $this->crearPedidoConItem($producto);

        $resena = Resena::create([
            'producto_id'    => $producto->id,
            'pedido_id'      => $pedido->id,
            'calificacion'   => 2,
            'nombre_cliente' => 'Ana García',
            'email_cliente'  => 'ana@example.com',
            'aprobada'       => true,
        ]);

        $admin = \App\Models\User::factory()->create(['es_admin' => true]);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\Admin\GestionResenas::class)
            ->call('rechazar', $resena->id);

        $this->assertFalse($resena->fresh()->aprobada);
    }
}
