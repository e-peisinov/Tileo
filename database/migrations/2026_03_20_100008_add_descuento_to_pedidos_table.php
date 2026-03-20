<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreignId('codigo_descuento_id')->nullable()->constrained('codigos_descuento')->nullOnDelete()->after('notas_admin');
            $table->decimal('monto_descuento', 8, 2)->default(0)->after('codigo_descuento_id');
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign(['codigo_descuento_id']);
            $table->dropColumn(['codigo_descuento_id', 'monto_descuento']);
        });
    }
};
