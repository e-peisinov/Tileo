<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uso_codigos_descuento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('codigo_descuento_id')->constrained('codigos_descuento')->cascadeOnDelete();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->string('email_cliente', 150);
            $table->decimal('monto_descontado', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uso_codigos_descuento');
    }
};
