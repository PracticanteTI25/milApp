<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Agrega relación de usuarios con roles y áreas.
     *
     * NOTA:
     * - Esto permite que un usuario real tenga permisos reales.
     * - Cuando llegue Directorio Activo, el usuario seguirá existiendo,
     *   pero su autenticación vendrá de AD.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Relación con roles (autorización)
            $table->foreignId('role_id')
                ->nullable()
                ->after('id')
                ->constrained('roles')
                ->nullOnDelete();

            // Relación con áreas (estructura organizacional)
            $table->foreignId('area_id')
                ->nullable()
                ->after('role_id')
                ->constrained('areas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['area_id']);
            $table->dropColumn(['role_id', 'area_id']);
        });
    }
};