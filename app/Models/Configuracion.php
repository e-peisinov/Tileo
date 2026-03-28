<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Configuracion extends Model
{
    protected $table = 'configuraciones';

    protected $fillable = ['clave', 'valor', 'tipo', 'etiqueta', 'descripcion'];

    public static function obtener(string $clave, mixed $porDefecto = null): mixed
    {
        $config = Cache::remember("configuracion_{$clave}", 300, function () use ($clave) {
            return static::where('clave', $clave)->first();
        });

        if (! $config) {
            return $porDefecto;
        }

        return match ($config->tipo) {
            'booleano' => filter_var($config->valor, FILTER_VALIDATE_BOOLEAN),
            'numero'   => (float) $config->valor,
            default    => $config->valor,
        };
    }

    public static function establecer(string $clave, mixed $valor): void
    {
        static::where('clave', $clave)->update(['valor' => $valor]);
        Cache::forget("configuracion_{$clave}");
    }
}
