<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suscriptor extends Model
{
    protected $table = 'suscriptores';

    protected $fillable = [
        'email', 'nombre', 'activo', 'origen',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
