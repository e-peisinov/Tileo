<?php

namespace Tests\Feature;

use App\Models\AvisoStock;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AvisoStockTest extends TestCase
{
    use RefreshDatabase;

    private function crearProducto(int $stock = 0): Producto
    {
        $categoria = Categoria::create([
            'nombre'      => 'Especias',
            'descripcion' => 'Especias',
            'activo'      => true,
        ]);

        return Producto::create([
            'nombre'       => 'Orégano',
            'descripcion'  => 'Orégano fresco',
            'precio'       => 900.00,
            'stock'        => $stock,
            'unidad'       => 'tubo',
            'activo'       => true,
            'destacado'    => false,
            'categoria_id' => $categoria->id,
        ]);
    }

    // ── Registro exitoso ────────────────────────────────────────────────────

    public function test_registrar_aviso_guarda_el_email_en_db(): void
    {
        $producto = $this->crearProducto(stock: 0);

        Livewire::test(\App\Livewire\NotificarStock::class, ['productoId' => $producto->id])
            ->set('emailAviso', 'cliente@example.com')
            ->call('registrar');

        $this->assertDatabaseHas('avisos_stock', [
            'producto_id' => $producto->id,
            'email'       => 'cliente@example.com',
            'enviado'     => false,
        ]);
    }

    public function test_registrar_aviso_marca_como_registrado(): void
    {
        $producto = $this->crearProducto(stock: 0);

        Livewire::test(\App\Livewire\NotificarStock::class, ['productoId' => $producto->id])
            ->set('emailAviso', 'cliente@example.com')
            ->call('registrar')
            ->assertSet('registrado', true)
            ->assertSet('yaRegistrado', false);
    }

    public function test_registrar_aviso_guarda_email_en_minusculas(): void
    {
        $producto = $this->crearProducto(stock: 0);

        Livewire::test(\App\Livewire\NotificarStock::class, ['productoId' => $producto->id])
            ->set('emailAviso', 'Cliente@Example.COM')
            ->call('registrar');

        $this->assertDatabaseHas('avisos_stock', [
            'email' => 'cliente@example.com',
        ]);
    }

    // ── Casos de bloqueo ────────────────────────────────────────────────────

    public function test_no_registra_aviso_si_el_producto_tiene_stock(): void
    {
        $producto = $this->crearProducto(stock: 5);

        Livewire::test(\App\Livewire\NotificarStock::class, ['productoId' => $producto->id])
            ->set('emailAviso', 'cliente@example.com')
            ->call('registrar')
            ->assertHasErrors('emailAviso');

        $this->assertDatabaseCount('avisos_stock', 0);
    }

    public function test_no_registra_aviso_duplicado_para_el_mismo_email(): void
    {
        $producto = $this->crearProducto(stock: 0);

        AvisoStock::create([
            'producto_id' => $producto->id,
            'email'       => 'cliente@example.com',
            'enviado'     => false,
        ]);

        Livewire::test(\App\Livewire\NotificarStock::class, ['productoId' => $producto->id])
            ->set('emailAviso', 'cliente@example.com')
            ->call('registrar')
            ->assertSet('yaRegistrado', true)
            ->assertSet('registrado', false);

        $this->assertDatabaseCount('avisos_stock', 1);
    }

    public function test_rechaza_email_invalido(): void
    {
        $producto = $this->crearProducto(stock: 0);

        Livewire::test(\App\Livewire\NotificarStock::class, ['productoId' => $producto->id])
            ->set('emailAviso', 'esto-no-es-un-email')
            ->call('registrar')
            ->assertHasErrors('emailAviso');

        $this->assertDatabaseCount('avisos_stock', 0);
    }

    public function test_rechaza_email_vacio(): void
    {
        $producto = $this->crearProducto(stock: 0);

        Livewire::test(\App\Livewire\NotificarStock::class, ['productoId' => $producto->id])
            ->set('emailAviso', '')
            ->call('registrar')
            ->assertHasErrors('emailAviso');
    }

    public function test_permite_distinto_email_para_el_mismo_producto(): void
    {
        $producto = $this->crearProducto(stock: 0);

        AvisoStock::create([
            'producto_id' => $producto->id,
            'email'       => 'primero@example.com',
            'enviado'     => false,
        ]);

        Livewire::test(\App\Livewire\NotificarStock::class, ['productoId' => $producto->id])
            ->set('emailAviso', 'segundo@example.com')
            ->call('registrar')
            ->assertSet('registrado', true);

        $this->assertDatabaseCount('avisos_stock', 2);
    }
}
