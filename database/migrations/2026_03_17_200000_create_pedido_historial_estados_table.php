<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_historial_estados', function (Blueprint $tabla) {
            $tabla->id();
            $tabla->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $tabla->string('estado_anterior', 30);
            $tabla->string('estado_nuevo', 30);
            $tabla->text('notas')->nullable();
            $tabla->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_historial_estados');
    }
};
