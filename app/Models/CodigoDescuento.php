<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CodigoDescuento extends Model
{
    protected $table = 'codigos_descuento';

    protected $fillable = [
        'codigo', 'tipo', 'valor', 'minimo_compra', 'usos_maximos',
        'usos_actuales', 'solo_un_uso_por_email', 'activo', 'expira_en',
    ];

    protected $casts = [
        'valor'                 => 'decimal:2',
        'minimo_compra'         => 'decimal:2',
        'activo'                => 'boolean',
        'solo_un_uso_por_email' => 'boolean',
        'expira_en'             => 'datetime',
    ];

    public function usos(): HasMany
    {
        return $this->hasMany(UsoCodigoDescuento::class);
    }

    public function estaVigente(): bool
    {
        if (! $this->activo) {
            return false;
        }

        if ($this->expira_en && $this->expira_en->isPast()) {
            return false;
        }

        if ($this->usos_maximos !== null && $this->usos_actuales >= $this->usos_maximos) {
            return false;
        }

        return true;
    }

    public function calcularDescuento(float $subtotal): float
    {
        if ($this->tipo === 'porcentaje') {
            return round($subtotal * ($this->valor / 100), 2);
        }

        return min((float) $this->valor, $subtotal);
    }

    public function yaUsadoPorEmail(string $email): bool
    {
        if (! $this->solo_un_uso_por_email) {
            return false;
        }

        return $this->usos()->where('email_cliente', $email)->exists();
    }
}
