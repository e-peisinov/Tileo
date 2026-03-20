<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';

    protected $fillable = [
        'titulo', 'subtitulo', 'imagen', 'url_destino', 'texto_boton',
        'color_fondo', 'activo', 'mostrar_desde', 'mostrar_hasta', 'orden',
    ];

    protected $casts = [
        'activo'        => 'boolean',
        'mostrar_desde' => 'datetime',
        'mostrar_hasta' => 'datetime',
    ];

    public function scopeVigentes($query)
    {
        return $query->where('activo', true)
            ->where(function ($q) {
                $q->whereNull('mostrar_desde')
                  ->orWhere('mostrar_desde', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('mostrar_hasta')
                  ->orWhere('mostrar_hasta', '>=', now());
            })
            ->orderBy('orden');
    }
}
