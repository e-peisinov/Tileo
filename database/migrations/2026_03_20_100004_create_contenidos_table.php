<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contenidos', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 100)->unique();
            $table->string('titulo', 200)->nullable();
            $table->longText('cuerpo')->nullable();
            $table->string('tipo', 50)->default('html');
            $table->string('etiqueta', 150)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contenidos');
    }
};
