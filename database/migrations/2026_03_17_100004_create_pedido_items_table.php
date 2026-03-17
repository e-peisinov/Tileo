<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_items', function (Blueprint $tabla) {
            $tabla->id();
            $tabla->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $tabla->foreignId('producto_id')->nullable()->constrained('productos')->onDelete('set null');
            $tabla->string('nombre_producto'); // snapshot
            $tabla->decimal('precio_unitario', 8, 2); // snapshot
            $tabla->integer('cantidad');
            $tabla->decimal('subtotal', 10, 2);
            $tabla->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_items');
    }
};
