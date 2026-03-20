<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AvisoStock extends Model
{
    protected $table = 'avisos_stock';

    protected $fillable = [
        'producto_id', 'email', 'nombre', 'enviado', 'enviado_en',
    ];

    protected $casts = [
        'enviado'    => 'boolean',
        'enviado_en' => 'datetime',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}
