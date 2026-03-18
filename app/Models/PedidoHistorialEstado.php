<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoHistorialEstado extends Model
{
    protected $table = 'pedido_historial_estados';

    protected $fillable = ['pedido_id', 'estado_anterior', 'estado_nuevo', 'notas'];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }
}
