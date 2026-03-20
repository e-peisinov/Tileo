<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 200);
            $table->string('subtitulo', 200)->nullable();
            $table->string('imagen', 255)->nullable();
            $table->string('url_destino', 255)->nullable();
            $table->string('texto_boton', 100)->nullable();
            $table->string('color_fondo', 20)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('mostrar_desde')->nullable();
            $table->timestamp('mostrar_hasta')->nullable();
            $table->tinyInteger('orden')->default(0);
            $table->timestamps();
            $table->index(['activo', 'mostrar_desde', 'mostrar_hasta']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
