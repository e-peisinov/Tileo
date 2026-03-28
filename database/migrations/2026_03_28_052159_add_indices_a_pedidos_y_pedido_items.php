<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // M6: índice en email_cliente (búsquedas en seguimiento y panel de clientes)
        // M7: índice en estado (filtros en dashboard, reportes y lista de pedidos)
        Schema::table('pedidos', function (Blueprint $table) {
            $table->index('email_cliente', 'pedidos_email_cliente_idx');
            $table->index('estado', 'pedidos_estado_idx');
        });

        // M8: FK de madera_id en pedido_items (integridad referencial)
        Schema::table('pedido_items', function (Blueprint $table) {
            $table->foreign('madera_id', 'pedido_items_madera_id_fk')
                  ->references('id')
                  ->on('maderas')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pedido_items', function (Blueprint $table) {
            $table->dropForeign('pedido_items_madera_id_fk');
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropIndex('pedidos_email_cliente_idx');
            $table->dropIndex('pedidos_estado_idx');
        });
    }
};
