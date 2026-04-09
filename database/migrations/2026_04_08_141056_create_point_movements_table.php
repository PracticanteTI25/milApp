<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('point_movements', function (Blueprint $table) {
            $table->id();

            // A quién pertenece el movimiento
            $table->foreignId('distributor_id')
                ->constrained('distributors')
                ->cascadeOnDelete();

            /**
             * delta: cambio de puntos
             *  +100  => carga manual
             *  -300  => redención
             */
            $table->integer('delta');

            /**
             * balance_after: saldo final luego del movimiento.
             * Esto sirve para auditoría (evita “recalcular” todo para mostrar saldo).
             */
            $table->unsignedInteger('balance_after');

            /**
             * Tipo de movimiento (para reportes y trazabilidad)
             * manual_credit   => comercial suma puntos
             * manual_debit    => comercial resta puntos (si llegara a usarse)
             * redemption      => compra/canje (resta puntos automáticamente)
             * adjustment      => corrección administrativa (si se necesita en el futuro)
             */
            $table->string('type', 30);

            // Comentario que escribe comercial (o nota del sistema)
            $table->string('comment')->nullable();

            // Usuario interno que ejecutó la acción (comercial/admin)
            $table->foreignId('created_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Más adelante: relacionar con un pedido si el movimiento fue redención
            $table->unsignedBigInteger('order_id')->nullable();

            $table->timestamps();

            // Índices para consultas rápidas
            $table->index(['distributor_id', 'created_at']);
            $table->index(['type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_movements');
    }
};
