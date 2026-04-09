<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            // Puntos actuales disponibles (saldo)
            $table->unsignedInteger('points_balance')->default(0);

            // Total de puntos redimidos históricamente (para reporting)
            $table->unsignedInteger('points_redeemed')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            $table->dropColumn(['points_balance', 'points_redeemed']);
        });
    }
};