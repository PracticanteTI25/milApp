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
        Schema::create('redencion_detalle', function (Blueprint $table) {
            $table->id();

            $table->foreignId('redencion_id')
                ->constrained('redenciones')
                ->cascadeOnDelete();

            $table->foreignId('bolsa_id')
                ->constrained('bolsas_puntos');

            $table->integer('puntos_usados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redencion_detalle');
    }
};
