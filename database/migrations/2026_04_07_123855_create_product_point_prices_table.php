<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Si la tabla ya existe, no hacemos nada
        if (Schema::hasTable('product_point_prices')) {
            return;
        }

        Schema::create('product_point_prices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();

            // Precio en puntos
            $table->unsignedInteger('points');

            // Vigencia del precio
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable(); // null = vigente

            $table->timestamps();

            $table->index(['product_id', 'starts_at']);
            $table->index(['product_id', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_point_prices');
    }
};
