<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Configuracion;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class ModoVacacionesTest extends TestCase
{
    use RefreshDatabase;

    private function crearConfiguracion(string $modoVacaciones = 'false', string $mensaje = 'Estamos de vacaciones.'): void
    {
        Configuracion::create([
            'clave'       => 'modo_vacaciones',
            'valor'       => $modoVacaciones,
            'tipo'        => 'booleano',
            'etiqueta'    => 'Modo vacaciones',
            'descripcion' => 'Bloquea el checkout.',
        ]);

        Configuracion::create([
            'clave'       => 'mensaje_vacaciones',
            'valor'       => $mensaje,
            'tipo'        => 'texto',
            'etiqueta'    => 'Mensaje de vacaciones',
            'descripcion' => 'Mensaje visible al cliente.',
        ]);
    }

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
            'precio'       => 1500.00,
            'stock'        => 10,
            'unidad'       => 'tubo',
            'activo'       => true,
            'destacado'    => false,
            'categoria_id' => $categoria->id,
        ]);
    }

    // ── Modo vacaciones activo ──────────────────────────────────────────────

    public function test_checkout_bloqueado_con_modo_vacaciones_activo(): void
    {
        $this->crearConfiguracion('true', 'Volvemos pronto.');

        $producto = $this->crearProducto();

        session()->put('carrito', [
            $producto->id => [
                'id'       => $producto->id,
                'nombre'   => $producto->nombre,
                'precio'   => 1500.00,
                'cantidad' => 1,
                'subtotal' => 1500.00,
                'imagen'   => null,
            ],
        ]);

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('nombre', 'Ana García')
            ->set('email', 'ana@example.com')
            ->set('telefono', '2324123456')
            ->set('metodo_entrega', 'retiro')
            ->set('metodo_pago', 'efectivo')
            ->call('confirmarPedido')
            ->assertHasErrors('carrito');

        $this->assertDatabaseCount('pedidos', 0);
    }

    public function test_checkout_permitido_con_modo_vacaciones_inactivo(): void
    {
        Mail::fake();

        $this->crearConfiguracion('false');

        $producto = $this->crearProducto();

        session()->put('carrito', [
            $producto->id => [
                'id'       => $producto->id,
                'nombre'   => $producto->nombre,
                'precio'   => 1500.00,
                'cantidad' => 1,
                'subtotal' => 1500.00,
                'imagen'   => null,
            ],
        ]);

        Livewire::test(\App\Livewire\Checkout::class)
            ->set('nombre', 'Ana García')
            ->set('email', 'ana@example.com')
            ->set('telefono', '2324123456')
            ->set('metodo_entrega', 'retiro')
            ->set('metodo_pago', 'efectivo')
            ->call('confirmarPedido');

        $this->assertDatabaseCount('pedidos', 1);
    }

    // ── Normalización del valor booleano ───────────────────────────────────

    public function test_configuracion_obtener_interpreta_string_true_como_booleano(): void
    {
        Configuracion::create([
            'clave'       => 'modo_vacaciones',
            'valor'       => 'true',
            'tipo'        => 'booleano',
            'etiqueta'    => 'Modo vacaciones',
            'descripcion' => 'Test',
        ]);

        $this->assertTrue(Configuracion::obtener('modo_vacaciones'));
    }

    public function test_configuracion_obtener_interpreta_string_false_como_booleano(): void
    {
        Configuracion::create([
            'clave'       => 'modo_vacaciones',
            'valor'       => 'false',
            'tipo'        => 'booleano',
            'etiqueta'    => 'Modo vacaciones',
            'descripcion' => 'Test',
        ]);

        $this->assertFalse(Configuracion::obtener('modo_vacaciones'));
    }

    public function test_configuracion_obtener_retorna_valor_por_defecto_si_no_existe(): void
    {
        $this->assertFalse(Configuracion::obtener('modo_vacaciones', false));
        $this->assertTrue(Configuracion::obtener('modo_vacaciones', true));
    }
}
