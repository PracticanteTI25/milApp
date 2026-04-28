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
        Schema::create('kardex_puntos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('distributor_id')
                ->constrained('distributors');

            $table->foreignId('bolsa_id')
                ->constrained('bolsas_puntos');

            $table->enum('tipo', [
                'generacion',
                'ajuste',
                'congelacion',
                'habilitacion',
                'consumo',
                'vencimiento'
            ]);

            $table->integer('puntos');
            $table->string('descripcion')->nullable();
            $table->timestamp('fecha');

            $table->timestamps();

            $table->index(['distributor_id', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kardex_puntos');
    }
};
