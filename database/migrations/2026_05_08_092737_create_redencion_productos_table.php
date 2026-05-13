<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redencion_productos', function (Blueprint $table) {
            $table->id();

            // Redención (pedido)
            $table->foreignId('redencion_id')
                ->constrained('redenciones')
                ->cascadeOnDelete();

            // Producto del catálogo
            $table->foreignId('product_id')
                ->constrained('products');

            // Cantidad en cajas
            $table->unsignedInteger('cantidad');

            // Puntos por caja en el momento del canje
            $table->unsignedInteger('puntos_unitarios');

            // Total de puntos consumidos por este producto
            $table->unsignedInteger('puntos_total');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redencion_productos');
    }
};
