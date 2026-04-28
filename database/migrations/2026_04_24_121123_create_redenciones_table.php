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
        Schema::create('redenciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('distributor_id')
                ->constrained('distributors');

            $table->foreignId('direccion_id')
                ->constrained('distributor_addresses');

            $table->timestamp('fecha');
            $table->integer('total_puntos_usados');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redenciones');
    }
};
