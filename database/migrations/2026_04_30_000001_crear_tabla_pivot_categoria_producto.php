<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Crear tabla pivot
        Schema::create('categoria_producto', function (Blueprint $table) {
            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->primary(['categoria_id', 'producto_id']);
        });

        // 2. Migrar datos existentes: cada producto pasa su categoria_id al pivot
        DB::table('productos')
            ->whereNotNull('categoria_id')
            ->orderBy('id')
            ->each(function ($producto) {
                DB::table('categoria_producto')->insertOrIgnore([
                    'categoria_id' => $producto->categoria_id,
                    'producto_id'  => $producto->id,
                ]);
            });

        // 3. Eliminar columna categoria_id de productos
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropColumn('categoria_id');
        });
    }

    public function down(): void
    {
        // 1. Re-agregar columna categoria_id (nullable para poder hacer el rollback)
        Schema::table('productos', function (Blueprint $table) {
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
        });

        // 2. Restaurar la primera categoría de cada producto desde el pivot
        DB::table('categoria_producto')
            ->orderBy('categoria_id')
            ->each(function ($fila) {
                DB::table('productos')
                    ->where('id', $fila->producto_id)
                    ->whereNull('categoria_id')
                    ->update(['categoria_id' => $fila->categoria_id]);
            });

        // 3. Eliminar tabla pivot
        Schema::dropIfExists('categoria_producto');
    }
};
