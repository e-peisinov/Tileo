<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedido_items', function (Blueprint $table) {
            $table->enum('tipo', ['producto', 'madera'])->default('producto')->after('subtotal');
            $table->unsignedBigInteger('madera_id')->nullable()->after('tipo');
            $table->json('condimentos')->nullable()->after('madera_id');
        });
    }

    public function down(): void
    {
        Schema::table('pedido_items', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'madera_id', 'condimentos']);
        });
    }
};
