<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    // El registro público está deshabilitado: solo el admin crea usuarios.

    public function test_registro_publico_redirige_al_login(): void
    {
        $this->get('/register')->assertRedirect('/login');
    }
}
