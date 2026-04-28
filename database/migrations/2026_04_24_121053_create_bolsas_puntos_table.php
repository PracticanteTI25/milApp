<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bolsas_puntos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('distributor_id')
                ->constrained('distributors');

            $table->date('mes'); // YYYY-MM-01

            $table->integer('puntos_generados')->default(0);
            $table->integer('puntos_disponibles')->default(0);

            $table->enum('estado', [
                'pendiente',
                'congelado',
                'habilitado',
                'vencido'
            ])->default('pendiente');

            $table->dateTime('fecha_habilitacion')->nullable();
            $table->dateTime('fecha_vencimiento')->nullable();

            $table->timestamps();

            $table->unique(['distributor_id', 'mes']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bolsas_puntos');
    }
};
