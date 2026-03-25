<?php

namespace Tests\Feature;

use App\Mail\ConfirmacionContactoMail;
use App\Mail\ContactoMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class ContactoTest extends TestCase
{
    use RefreshDatabase;

    // ── Envío exitoso ───────────────────────────────────────────────────────

    public function test_formulario_envia_email_al_admin(): void
    {
        Mail::fake();

        Livewire::test(\App\Livewire\Contacto::class)
            ->set('nombre', 'Juan Pérez')
            ->set('email', 'juan@example.com')
            ->set('telefono', '2324999888')
            ->set('asunto', 'Consulta sobre orégano')
            ->set('mensaje', 'Quiero saber si tienen disponible orégano fresco.')
            ->call('enviar');

        Mail::assertSent(ContactoMail::class);
    }

    public function test_formulario_envia_confirmacion_al_cliente(): void
    {
        Mail::fake();

        Livewire::test(\App\Livewire\Contacto::class)
            ->set('nombre', 'Juan Pérez')
            ->set('email', 'juan@example.com')
            ->set('telefono', '2324999888')
            ->set('asunto', 'Consulta')
            ->set('mensaje', 'Quiero saber si tienen disponible orégano fresco.')
            ->call('enviar');

        Mail::assertSent(ConfirmacionContactoMail::class, fn ($mail) =>
            $mail->hasTo('juan@example.com')
        );
    }

    public function test_formulario_marca_como_enviado(): void
    {
        Mail::fake();

        Livewire::test(\App\Livewire\Contacto::class)
            ->set('nombre', 'Juan Pérez')
            ->set('email', 'juan@example.com')
            ->set('telefono', '2324999888')
            ->set('mensaje', 'Quiero saber más sobre sus productos artesanales.')
            ->call('enviar')
            ->assertSet('enviado', true);
    }

    // ── Validaciones ────────────────────────────────────────────────────────

    public function test_rechaza_nombre_vacio(): void
    {
        Livewire::test(\App\Livewire\Contacto::class)
            ->set('nombre', '')
            ->set('email', 'juan@example.com')
            ->set('telefono', '2324999888')
            ->set('mensaje', 'Mensaje de prueba para el test.')
            ->call('enviar')
            ->assertHasErrors('nombre');
    }

    public function test_rechaza_email_invalido(): void
    {
        Livewire::test(\App\Livewire\Contacto::class)
            ->set('nombre', 'Juan')
            ->set('email', 'no-es-un-email')
            ->set('telefono', '2324999888')
            ->set('mensaje', 'Mensaje de prueba para el test.')
            ->call('enviar')
            ->assertHasErrors('email');
    }

    public function test_rechaza_mensaje_muy_corto(): void
    {
        Livewire::test(\App\Livewire\Contacto::class)
            ->set('nombre', 'Juan')
            ->set('email', 'juan@example.com')
            ->set('telefono', '2324999888')
            ->set('mensaje', 'Hola')
            ->call('enviar')
            ->assertHasErrors('mensaje');
    }

    public function test_rechaza_telefono_muy_corto(): void
    {
        Livewire::test(\App\Livewire\Contacto::class)
            ->set('nombre', 'Juan')
            ->set('email', 'juan@example.com')
            ->set('telefono', '123')
            ->set('mensaje', 'Mensaje de prueba para el test.')
            ->call('enviar')
            ->assertHasErrors('telefono');
    }
}
