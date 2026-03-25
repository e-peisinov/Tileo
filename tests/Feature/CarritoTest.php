<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CarritoTest extends TestCase
{
    use RefreshDatabase;

    private function crearProducto(array $atributos = []): Producto
    {
        $categoria = Categoria::create([
            'nombre'      => 'Especias',
            'descripcion' => 'Especias',
            'activo'      => true,
        ]);

        return Producto::create(array_merge([
            'nombre'       => 'Pimentón',
            'descripcion'  => 'Pimentón',
            'precio'       => 1500.00,
            'stock'        => 10,
            'unidad'       => 'tubo',
            'activo'       => true,
            'destacado'    => false,
            'categoria_id' => $categoria->id,
        ], $atributos));
    }

    private function itemEnCarrito(Producto $producto, int $cantidad = 1): array
    {
        return [
            'id'       => $producto->id,
            'nombre'   => $producto->nombre,
            'precio'   => (float) $producto->precio,
            'cantidad' => $cantidad,
            'subtotal' => (float) $producto->precio * $cantidad,
            'imagen'   => null,
            'unidad'   => $producto->unidad,
            'stock'    => $producto->stock,
        ];
    }

    // ── Agregar producto ────────────────────────────────────────────────────

    public function test_agregar_producto_lo_incorpora_al_carrito(): void
    {
        $producto = $this->crearProducto();

        Livewire::test(\App\Livewire\Carrito::class)
            ->call('agregarProducto', $producto->id);

        $carrito = session('carrito', []);
        $this->assertArrayHasKey($producto->id, $carrito);
        $this->assertEquals(1, $carrito[$producto->id]['cantidad']);
    }

    public function test_agregar_producto_abre_el_carrito(): void
    {
        $producto = $this->crearProducto();

        Livewire::test(\App\Livewire\Carrito::class)
            ->call('agregarProducto', $producto->id)
            ->assertSet('abierto', true);
    }

    public function test_agregar_dos_veces_el_mismo_producto_acumula_cantidad(): void
    {
        $producto = $this->crearProducto(['stock' => 10]);

        $component = Livewire::test(\App\Livewire\Carrito::class);
        $component->call('agregarProducto', $producto->id);
        $component->call('agregarProducto', $producto->id);

        $carrito = session('carrito', []);
        $this->assertEquals(2, $carrito[$producto->id]['cantidad']);
    }

    public function test_agregar_producto_no_supera_el_stock_disponible(): void
    {
        $producto = $this->crearProducto(['stock' => 2]);

        $component = Livewire::test(\App\Livewire\Carrito::class);
        $component->call('agregarProducto', $producto->id);
        $component->call('agregarProducto', $producto->id);
        $component->call('agregarProducto', $producto->id); // tercero, queda en 2

        $carrito = session('carrito', []);
        $this->assertEquals(2, $carrito[$producto->id]['cantidad']);
    }

    public function test_no_se_agrega_producto_sin_stock(): void
    {
        $producto = $this->crearProducto(['stock' => 0]);

        Livewire::test(\App\Livewire\Carrito::class)
            ->call('agregarProducto', $producto->id);

        $carrito = session('carrito', []);
        $this->assertArrayNotHasKey($producto->id, $carrito);
    }

    public function test_no_se_agrega_producto_inactivo(): void
    {
        $producto = $this->crearProducto(['activo' => false, 'stock' => 5]);

        Livewire::test(\App\Livewire\Carrito::class)
            ->call('agregarProducto', $producto->id);

        $carrito = session('carrito', []);
        $this->assertArrayNotHasKey($producto->id, $carrito);
    }

    // ── Remover item ────────────────────────────────────────────────────────

    public function test_remover_item_lo_elimina_del_carrito(): void
    {
        $producto = $this->crearProducto();

        session()->put('carrito', [$producto->id => $this->itemEnCarrito($producto, 2)]);

        Livewire::test(\App\Livewire\Carrito::class)
            ->call('removerItem', $producto->id);

        $carrito = session('carrito', []);
        $this->assertArrayNotHasKey($producto->id, $carrito);
    }

    // ── Actualizar cantidad ─────────────────────────────────────────────────

    public function test_actualizar_cantidad_modifica_el_item_y_el_subtotal(): void
    {
        $producto = $this->crearProducto(['precio' => 1000.00, 'stock' => 10]);

        session()->put('carrito', [$producto->id => $this->itemEnCarrito($producto, 1)]);

        Livewire::test(\App\Livewire\Carrito::class)
            ->call('actualizarCantidad', $producto->id, 4);

        $carrito = session('carrito', []);
        $this->assertEquals(4, $carrito[$producto->id]['cantidad']);
        $this->assertEquals(4000.00, $carrito[$producto->id]['subtotal']);
    }

    public function test_actualizar_cantidad_a_cero_remueve_el_item(): void
    {
        $producto = $this->crearProducto();

        session()->put('carrito', [$producto->id => $this->itemEnCarrito($producto, 2)]);

        Livewire::test(\App\Livewire\Carrito::class)
            ->call('actualizarCantidad', $producto->id, 0);

        $carrito = session('carrito', []);
        $this->assertArrayNotHasKey($producto->id, $carrito);
    }

    public function test_actualizar_cantidad_no_supera_el_stock(): void
    {
        $producto = $this->crearProducto(['stock' => 3]);

        session()->put('carrito', [$producto->id => $this->itemEnCarrito($producto, 1)]);

        Livewire::test(\App\Livewire\Carrito::class)
            ->call('actualizarCantidad', $producto->id, 99); // solicita 99, hay 3

        $carrito = session('carrito', []);
        $this->assertEquals(3, $carrito[$producto->id]['cantidad']);
    }

    // ── Vaciar carrito ──────────────────────────────────────────────────────

    public function test_vaciar_carrito_elimina_todos_los_items(): void
    {
        $producto = $this->crearProducto();

        session()->put('carrito', [$producto->id => $this->itemEnCarrito($producto)]);

        Livewire::test(\App\Livewire\Carrito::class)
            ->call('vaciarCarrito');

        $this->assertNull(session('carrito'));
    }

    // ── Totales ─────────────────────────────────────────────────────────────

    public function test_total_se_calcula_correctamente(): void
    {
        $producto = $this->crearProducto(['precio' => 1000.00]);

        session()->put('carrito', [$producto->id => $this->itemEnCarrito($producto, 3)]);

        $total = collect(session('carrito', []))->sum('subtotal');
        $this->assertEquals(3000.00, $total);
    }

    // ── Abrir / cerrar ──────────────────────────────────────────────────────

    public function test_abrir_y_cerrar_carrito(): void
    {
        Livewire::test(\App\Livewire\Carrito::class)
            ->assertSet('abierto', false)
            ->call('abrirCarrito')
            ->assertSet('abierto', true)
            ->call('cerrarCarrito')
            ->assertSet('abierto', false);
    }
}
