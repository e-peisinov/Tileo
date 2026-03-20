<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avisos_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->string('email', 150);
            $table->string('nombre', 100)->nullable();
            $table->boolean('enviado')->default(false);
            $table->timestamp('enviado_en')->nullable();
            $table->timestamps();
            $table->index(['producto_id', 'enviado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avisos_stock');
    }
};
