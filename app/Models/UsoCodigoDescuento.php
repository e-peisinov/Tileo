<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsoCodigoDescuento extends Model
{
    protected $table = 'uso_codigos_descuento';

    protected $fillable = [
        'codigo_descuento_id', 'pedido_id', 'email_cliente', 'monto_descontado',
    ];

    protected $casts = [
        'monto_descontado' => 'decimal:2',
    ];

    public function codigoDescuento(): BelongsTo
    {
        return $this->belongsTo(CodigoDescuento::class);
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }
}
