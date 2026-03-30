<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Crea la tabla AREAS.
     *
     * Esta tabla representa las áreas de la empresa (Comercial, Marketing, etc.).
     * IMPORTANTE:
     * - No otorga permisos directamente.
     * - Sirve para clasificar usuarios y construir el sidebar.
     */
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            // Recomendado en MySQL para llaves foráneas
            $table->engine = 'InnoDB';

            $table->id(); // bigint unsigned
            $table->string('name')->unique(); // Nombre visible (Comercial, Marketing...)
            $table->string('slug')->unique(); // Identificador técnico (comercial, marketing...)
            $table->boolean('active')->default(true); // Permite desactivar sin borrar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
