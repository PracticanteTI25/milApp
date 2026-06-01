<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devoluciones', function (Blueprint $table) {
            $table->id();

            // Relación con distribuidor
            $table->foreignId('distributor_id')
                ->constrained('distributors')
                ->cascadeOnDelete();

            // Datos del formulario
            $table->string('lote'); 
            $table->integer('cantidad');

            // Imagen
            $table->string('imagen_path');

            // Texto
            $table->text('observaciones');

            // Estado del proceso (muy importante)
            $table->enum('estado', [
                'pendiente',
                'en_revision',
                'aprobado',
                'rechazado'
            ])->default('pendiente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devoluciones');
    }
};
