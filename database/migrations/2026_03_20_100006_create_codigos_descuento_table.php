<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('codigos_descuento', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->enum('tipo', ['porcentaje', 'monto_fijo']);
            $table->decimal('valor', 8, 2);
            $table->decimal('minimo_compra', 8, 2)->nullable();
            $table->integer('usos_maximos')->nullable();
            $table->integer('usos_actuales')->default(0);
            $table->boolean('solo_un_uso_por_email')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamp('expira_en')->nullable();
            $table->timestamps();
            $table->index(['codigo', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('codigos_descuento');
    }
};
