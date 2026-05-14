<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kardex_puntos', function (Blueprint $table) {

            $table->enum('impacto', [
                'suma_habilitada',
                'suma_congelada',
                'resta',
            ])
                ->after('tipo')
                ->nullable()
                ->comment('Define cómo impacta el movimiento en el saldo');
        });
    }

    public function down(): void
    {
        Schema::table('kardex_puntos', function (Blueprint $table) {
            $table->dropColumn('impacto');
        });
    }
};
