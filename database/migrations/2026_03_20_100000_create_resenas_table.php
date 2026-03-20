<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resenas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->foreignId('pedido_id')->constrained('pedidos')->cascadeOnDelete();
            $table->string('nombre_cliente', 100);
            $table->string('email_cliente', 150);
            $table->tinyInteger('calificacion')->unsigned();
            $table->text('comentario')->nullable();
            $table->boolean('aprobada')->default(false);
            $table->timestamps();
            $table->unique(['producto_id', 'pedido_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resenas');
    }
};
