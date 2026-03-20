<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\CodigoDescuento;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\UsoCodigoDescuento;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class CodigoDescuentoTest extends TestCase
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
            'descripcion'  => 'Pimentón',
            'precio'       => 1000.00,
            'stock'        => 20,
            'unidad'       => 'tubo',
            'activo'       => true,
            'destacado'    => false,
            'categoria_id' => $categoria->id,
        ]);
    }

    private function configurarCarrito(Producto $producto): void
    {
        session()->put('carrito', [
            $producto->id => [
                'id'       => $producto->id,
                'nombre'   => $producto->nombre,
                'precio'   => 1000.00,
                'cantidad' => 2,
                'subtotal' => 2000.00,
                'imagen'   => null,
            ],
        ]);
    }

    // ── Validación de códigos ────────────────────────────────────────────────

    public function test_codigo_valido_aplica_descuento_porcentaje(): void
    {
        $producto = $this->crearProducto();
        $this->configurarCarrito($producto);

        $codigo = CodigoDescuento::create([
            'codigo'               => 'DESC10',
            'tipo'                 => 'porcentaje',
            'valor'                => 10.00,
            'activo'               => true,
            'usos_actuales'        => 0,
            'solo_un_uso_por_email' => false,
        ]);

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('codigoDescuentoInput', 'DESC10')
            ->call('aplicarDescuento')
            ->assertSet('descuentoExitoso', true)
            ->assertSet('montoDescuento', 200.00); // 10% de 2000
    }

    public function test_codigo_valido_aplica_descuento_monto_fijo(): void
    {
        $producto = $this->crearProducto();
        $this->configurarCarrito($producto);

        CodigoDescuento::create([
            'codigo'               => 'FIJO500',
            'tipo'                 => 'monto_fijo',
            'valor'                => 500.00,
            'activo'               => true,
            'usos_actuales'        => 0,
            'solo_un_uso_por_email' => false,
        ]);

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('codigoDescuentoInput', 'FIJO500')
            ->call('aplicarDescuento')
            ->assertSet('descuentoExitoso', true)
            ->assertSet('montoDescuento', 500.00);
    }

    public function test_codigo_inexistente_es_rechazado(): void
    {
        $producto = $this->crearProducto();
        $this->configurarCarrito($producto);

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('codigoDescuentoInput', 'NOEXISTE')
            ->call('aplicarDescuento')
            ->assertSet('descuentoExitoso', false)
            ->assertSet('montoDescuento', 0.0);
    }

    public function test_codigo_inactivo_es_rechazado(): void
    {
        $producto = $this->crearProducto();
        $this->configurarCarrito($producto);

        CodigoDescuento::create([
            'codigo'               => 'INACTIVO',
            'tipo'                 => 'porcentaje',
            'valor'                => 10.00,
            'activo'               => false, // inactivo
            'usos_actuales'        => 0,
            'solo_un_uso_por_email' => false,
        ]);

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('codigoDescuentoInput', 'INACTIVO')
            ->call('aplicarDescuento')
            ->assertSet('descuentoExitoso', false);
    }

    public function test_codigo_expirado_es_rechazado(): void
    {
        $producto = $this->crearProducto();
        $this->configurarCarrito($producto);

        CodigoDescuento::create([
            'codigo'               => 'VENCIDO',
            'tipo'                 => 'porcentaje',
            'valor'                => 10.00,
            'activo'               => true,
            'expira_en'            => now()->subDay(), // ya venció
            'usos_actuales'        => 0,
            'solo_un_uso_por_email' => false,
        ]);

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('codigoDescuentoInput', 'VENCIDO')
            ->call('aplicarDescuento')
            ->assertSet('descuentoExitoso', false);
    }

    public function test_codigo_con_usos_maximos_agotados_es_rechazado(): void
    {
        $producto = $this->crearProducto();
        $this->configurarCarrito($producto);

        CodigoDescuento::create([
            'codigo'               => 'AGOTADO',
            'tipo'                 => 'porcentaje',
            'valor'                => 10.00,
            'activo'               => true,
            'usos_maximos'         => 5,
            'usos_actuales'        => 5, // ya alcanzó el máximo
            'solo_un_uso_por_email' => false,
        ]);

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('codigoDescuentoInput', 'AGOTADO')
            ->call('aplicarDescuento')
            ->assertSet('descuentoExitoso', false);
    }

    public function test_codigo_rechaza_compra_bajo_minimo(): void
    {
        $producto = $this->crearProducto();
        $this->configurarCarrito($producto); // subtotal = 2000

        CodigoDescuento::create([
            'codigo'               => 'MINIMO',
            'tipo'                 => 'porcentaje',
            'valor'                => 10.00,
            'activo'               => true,
            'minimo_compra'        => 5000.00, // mínimo mayor al subtotal
            'usos_actuales'        => 0,
            'solo_un_uso_por_email' => false,
        ]);

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('codigoDescuentoInput', 'MINIMO')
            ->call('aplicarDescuento')
            ->assertSet('descuentoExitoso', false);
    }

    public function test_codigo_un_uso_por_email_rechaza_segundo_uso(): void
    {
        $producto = $this->crearProducto();
        $this->configurarCarrito($producto);

        $codigo = CodigoDescuento::create([
            'codigo'               => 'UNICO',
            'tipo'                 => 'porcentaje',
            'valor'                => 10.00,
            'activo'               => true,
            'usos_actuales'        => 1,
            'solo_un_uso_por_email' => true,
        ]);

        // Simular uso previo por este email
        $pedido = Pedido::create([
            'nombre_cliente'   => 'Test',
            'email_cliente'    => 'repetido@example.com',
            'telefono_cliente' => '123456789',
            'metodo_entrega'   => 'retiro',
            'metodo_pago'      => 'efectivo',
            'subtotal'         => 1000,
            'total'            => 1000,
            'estado'           => 'pendiente',
        ]);

        UsoCodigoDescuento::create([
            'codigo_descuento_id' => $codigo->id,
            'pedido_id'           => $pedido->id,
            'email_cliente'       => 'repetido@example.com',
            'monto_descontado'    => 100,
        ]);

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('codigoDescuentoInput', 'UNICO')
            ->set('email', 'repetido@example.com')
            ->call('aplicarDescuento')
            ->assertSet('descuentoExitoso', false);
    }

    public function test_codigo_se_registra_en_uso_al_confirmar_pedido(): void
    {
        Mail::fake();

        $producto = $this->crearProducto();
        $this->configurarCarrito($producto);

        CodigoDescuento::create([
            'codigo'               => 'DESC10',
            'tipo'                 => 'porcentaje',
            'valor'                => 10.00,
            'activo'               => true,
            'usos_actuales'        => 0,
            'solo_un_uso_por_email' => false,
        ]);

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('nombre', 'Ana García')
            ->set('email', 'ana@example.com')
            ->set('telefono', '2324123456')
            ->set('metodo_entrega', 'retiro')
            ->set('metodo_pago', 'efectivo')
            ->set('codigoDescuentoInput', 'DESC10')
            ->call('aplicarDescuento')
            ->call('confirmarPedido');

        $this->assertDatabaseCount('uso_codigos_descuento', 1);
        $this->assertDatabaseHas('uso_codigos_descuento', ['email_cliente' => 'ana@example.com']);
    }
}
