<?php

namespace App\Models;

use App\Models\Madera;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoItem extends Model
{
    protected $table = 'pedido_items';

    protected $fillable = [
        'pedido_id', 'producto_id',
        'nombre_producto', 'precio_unitario',
        'cantidad', 'subtotal',
        'tipo', 'madera_id', 'condimentos',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal'        => 'decimal:2',
        'condimentos'     => 'array',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function madera(): BelongsTo
    {
        return $this->belongsTo(Madera::class);
    }
}
