<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Madera extends Model
{
    protected $table = 'maderas';

    protected $fillable = [
        'nombre', 'descripcion', 'capacidad', 'precio', 'imagen', 'activo',
    ];

    protected $casts = [
        'precio'  => 'decimal:2',
        'activo'  => 'boolean',
    ];
}
