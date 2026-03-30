<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Crea la tabla de roles del sistema.
     *
     * Un rol representa un conjunto de permisos técnicos
     * (NO es un área organizacional).
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            // InnoDB es obligatorio para claves foráneas en MySQL
            $table->engine = 'InnoDB';

            $table->id(); // bigint unsigned, compatible con foreignId
            $table->string('name'); // Nombre legible
            $table->string('slug')->unique(); // Identificador técnico
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
