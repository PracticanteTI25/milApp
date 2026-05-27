<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Crea la tabla point_lots.
     *
     * Esta tabla representa lotes de puntos homogéneos:
     * - mismo origen
     * - misma fecha de habilitación
     * - misma fecha de vencimiento
     *
     * NO reemplaza bolsas_puntos ni kardex_puntos.
     * Es una capa adicional para trazabilidad, FIFO y auditoría.
     */
    public function up(): void
    {
        Schema::create('point_lots', function (Blueprint $table) {

            $table->id();

            // A quién pertenecen los puntos
            $table->foreignId('distributor_id')
                ->constrained('distributors')
                ->cascadeOnDelete();

            // Bolsa mensual a la que pertenece el lote
            $table->foreignId('bolsa_id')
                ->constrained('bolsas_puntos')
                ->cascadeOnDelete();

            /**
             * Origen del lote:
             * - manual: asignado por un usuario (ajustes)
             * - generado: creado automáticamente por el sistema
             */
            $table->enum('source', ['manual', 'generado']);

            // Cantidad inicial de puntos del lote
            $table->unsignedInteger('points_initial');

            // Puntos vivos actualmente (para consumo y vencimiento)
            $table->unsignedInteger('points_remaining');

            /**
             * Fecha real de habilitación de los puntos.
             * Proviene del kardex (movimiento de habilitación).
             */
            $table->timestamp('fecha_habilitacion');

            /**
             * Fecha real de vencimiento.
             * Se calcula al crear el lote y NO se recalcula.
             */
            $table->timestamp('fecha_vencimiento')->nullable();

            /**
             * Estado actual del lote:
             * - congelado: aún no disponible
             * - disponible: puede consumirse
             * - vencido: expiró
             * - consumido: points_remaining = 0
             */
            $table->enum('status', [
                'congelado',
                'disponible',
                'vencido',
                'consumido',
            ])->default('congelado');

            $table->timestamps();

            // Índices para rendimiento
            $table->index(['distributor_id', 'status']);
            $table->index(['fecha_vencimiento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_lots');
    }
};
