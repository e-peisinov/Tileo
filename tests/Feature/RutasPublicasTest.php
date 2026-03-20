<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RutasPublicasTest extends TestCase
{
    use RefreshDatabase;

    // ── Rutas públicas ──────────────────────────────────────────────────────

    public function test_inicio_es_accesible(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_catalogo_es_accesible(): void
    {
        $this->get('/catalogo')->assertOk();
    }

    public function test_nosotros_es_accesible(): void
    {
        $this->get('/nosotros')->assertOk();
    }

    public function test_contacto_es_accesible(): void
    {
        $this->get('/contacto')->assertOk();
    }

    public function test_preguntas_es_accesible(): void
    {
        $this->get('/preguntas')->assertOk();
    }

    public function test_terminos_es_accesible(): void
    {
        $this->get('/terminos')->assertOk();
    }

    public function test_privacidad_es_accesible(): void
    {
        $this->get('/privacidad')->assertOk();
    }

    public function test_seguimiento_es_accesible(): void
    {
        $this->get('/seguimiento')->assertOk();
    }

    public function test_checkout_es_accesible(): void
    {
        $this->get('/checkout')->assertOk();
    }

    // ── Rutas admin — protección ────────────────────────────────────────────

    public function test_admin_redirige_sin_autenticar(): void
    {
        $this->get('/admin')->assertRedirect();
    }

    public function test_admin_pedidos_redirige_sin_autenticar(): void
    {
        $this->get('/admin/pedidos')->assertRedirect();
    }

    public function test_admin_devuelve_403_a_usuario_sin_privilegios(): void
    {
        $usuario = User::factory()->create(['es_admin' => false]);

        $this->actingAs($usuario)->get('/admin')->assertForbidden();
    }

    public function test_admin_accesible_para_administrador(): void
    {
        $admin = User::factory()->create(['es_admin' => true]);

        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_admin_todas_las_secciones_son_accesibles(): void
    {
        $admin = User::factory()->create(['es_admin' => true]);

        $rutas = [
            '/admin',
            '/admin/pedidos',
            '/admin/productos',
            '/admin/categorias',
            '/admin/usuarios',
            '/admin/configuracion',
            '/admin/clientes',
            '/admin/suscriptores',
            '/admin/resenas',
            '/admin/banners',
            '/admin/contenidos',
            '/admin/codigos-descuento',
            '/admin/reportes',
        ];

        foreach ($rutas as $ruta) {
            $this->actingAs($admin)->get($ruta)->assertOk("Fallo en: {$ruta}");
        }
    }

    // ── SEO ─────────────────────────────────────────────────────────────────

    public function test_inicio_tiene_meta_descripcion(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<meta name="description"', false);
    }

    public function test_inicio_tiene_og_tags(): void
    {
        $respuesta = $this->get('/');

        $respuesta->assertSee('og:title', false);
        $respuesta->assertSee('og:description', false);
    }
}
