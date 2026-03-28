<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Resena extends Model
{
    protected $table = 'resenas';

    protected $fillable = [
        'producto_id', 'pedido_id', 'nombre_cliente', 'email_cliente',
        'calificacion', 'comentario', 'aprobada',
    ];

    protected $casts = [
        'calificacion' => 'integer',
        'aprobada'     => 'boolean',
    ];

    protected static function booted(): void
    {
        $invalidar = fn (self $resena) => Cache::forget("promedio_calificacion_{$resena->producto_id}");
        static::saved($invalidar);
        static::deleted($invalidar);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function scopeAprobadas($query)
    {
        return $query->where('aprobada', true);
    }
}
