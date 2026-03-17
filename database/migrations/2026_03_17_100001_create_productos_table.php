<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $tabla) {
            $tabla->id();
            $tabla->foreignId('categoria_id')->constrained('categorias')->onDelete('restrict');
            $tabla->string('nombre');
            $tabla->text('descripcion')->nullable();
            $tabla->decimal('precio', 8, 2)->default(0);
            $tabla->integer('stock')->default(0);
            $tabla->string('unidad')->default('unidad'); // ej: "50g", "100ml", "frasco"
            $tabla->string('imagen')->nullable();
            $tabla->boolean('activo')->default(true);
            $tabla->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
