<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $tabla) {
            $tabla->id();
            $tabla->string('numero_pedido')->unique()->nullable();
            $tabla->string('nombre_cliente');
            $tabla->string('email_cliente');
            $tabla->string('telefono_cliente');
            $tabla->enum('metodo_entrega', ['envio', 'retiro'])->default('retiro');
            $tabla->enum('metodo_pago', ['transferencia', 'efectivo'])->default('efectivo');
            $tabla->text('direccion_envio')->nullable();
            $tabla->decimal('costo_envio', 8, 2)->nullable();
            $tabla->decimal('subtotal', 10, 2)->default(0);
            $tabla->decimal('total', 10, 2)->default(0);
            $tabla->enum('estado', [
                'pendiente',
                'confirmado',
                'preparando',
                'enviado',
                'listo_retiro',
                'entregado',
                'rechazado',
                'cancelado',
            ])->default('pendiente');
            $tabla->text('notas_cliente')->nullable();
            $tabla->text('notas_admin')->nullable();
            $tabla->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
