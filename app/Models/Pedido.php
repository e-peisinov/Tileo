<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'numero_pedido', 'nombre_cliente', 'email_cliente', 'telefono_cliente',
        'metodo_entrega', 'metodo_pago', 'direccion_envio',
        'costo_envio', 'subtotal', 'total', 'estado',
        'notas_cliente', 'notas_admin',
    ];

    protected $casts = [
        'subtotal'    => 'decimal:2',
        'total'       => 'decimal:2',
        'costo_envio' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::created(function (Pedido $pedido) {
            $pedido->updateQuietly([
                'numero_pedido' => 'TIL-' . str_pad($pedido->id, 4, '0', STR_PAD_LEFT),
            ]);
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function historial(): HasMany
    {
        return $this->hasMany(PedidoHistorialEstado::class)->latest();
    }

    public function etiquetaEstado(): string
    {
        return static::etiquetaParaEstado($this->estado ?? 'pendiente');
    }

    public function colorEstado(): string
    {
        return static::colorParaEstado($this->estado ?? 'pendiente');
    }

    public static function etiquetaParaEstado(string $estado): string
    {
        return match ($estado) {
            'pendiente'    => 'Pendiente',
            'confirmado'   => 'Confirmado',
            'preparando'   => 'Preparando',
            'enviado'      => 'Enviado',
            'listo_retiro' => 'Listo para retirar',
            'entregado'    => 'Entregado',
            'rechazado'    => 'Rechazado',
            'cancelado'    => 'Cancelado',
            default        => $estado,
        };
    }

    public static function colorParaEstado(string $estado): string
    {
        return match ($estado) {
            'pendiente'    => '#8b5e3c',
            'confirmado'   => '#386641',
            'preparando'   => '#a7c957',
            'enviado'      => '#386641',
            'listo_retiro' => '#386641',
            'entregado'    => '#2c1a0e',
            'rechazado'    => '#c0392b',
            'cancelado'    => '#999',
            default        => '#2c1a0e',
        };
    }

    public function calcularTotal(): void
    {
        $this->total = $this->subtotal + ($this->costo_envio ?? 0);
        $this->save();
    }
}
