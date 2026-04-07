<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('distributor_addresses', function (Blueprint $table) {
            $table->id();

            // Relación con distributors
            $table->foreignId('distributor_id')
                ->constrained('distributors')
                ->cascadeOnDelete();

            // Formato tipo checkout (sin nombre/apellido)
            $table->string('country')->default('Colombia'); // País/Región
            $table->string('address_line1');                // Dirección
            $table->string('address_line2')->nullable();    // Apto, torre, etc.
            $table->string('city');                         // Ciudad
            $table->string('state');                        // Departamento
            $table->string('postal_code')->nullable();      // Código postal (opcional)
            $table->string('phone')->nullable();            // Teléfono (opcional)

            // Por si luego permites más direcciones
            $table->boolean('is_default')->default(true);

            $table->timestamps();

            $table->index(['distributor_id', 'is_default']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distributor_addresses');
    }
};