<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contenido extends Model
{
    protected $table = 'contenidos';

    protected $fillable = [
        'clave', 'titulo', 'cuerpo', 'tipo', 'etiqueta',
    ];

    public static function obtener(string $clave, string $porDefecto = ''): string
    {
        $contenido = static::where('clave', $clave)->first();
        return $contenido?->cuerpo ?? $porDefecto;
    }

    public static function establecer(string $clave, string $valor): void
    {
        static::where('clave', $clave)->update(['cuerpo' => $valor]);
    }
}
