<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EsAdmin
{
    public function handle(Request $solicitud, Closure $siguiente): Response
    {
        if (! $solicitud->user() || ! $solicitud->user()->es_admin) {
            abort(403, 'Acceso no autorizado.');
        }

        return $siguiente($solicitud);
    }
}
