<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'categoria_id', 'nombre', 'descripcion',
        'precio', 'stock', 'unidad', 'imagen', 'activo', 'destacado',
    ];

    protected $casts = [
        'precio'    => 'decimal:2',
        'activo'    => 'boolean',
        'destacado' => 'boolean',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function pedidoItems(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function hayStock(int $cantidad = 1): bool
    {
        return $this->stock >= $cantidad;
    }
}
