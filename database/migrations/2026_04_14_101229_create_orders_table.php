<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Distribuidora que hace el canje
            $table->foreignId('distributor_id')
                ->constrained('distributors')
                ->cascadeOnDelete();

            // Total de puntos usados en el canje
            $table->unsignedInteger('total_points');

            /**
             * Estados del pedido:
             * - pendiente (recién creado)
             * - confirmado
             * - en_proceso
             * - enviado
             * - cancelado
             */
            $table->string('status', 30)->default('pendiente');

            // Metadata futura (dirección, observaciones, etc.)
            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['distributor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};