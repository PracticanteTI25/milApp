<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            // Solo agrega si no existe (evita Duplicate column)
            if (!Schema::hasColumn('distributors', 'points_balance')) {
                $table->unsignedInteger('points_balance')->default(0);
            }

            if (!Schema::hasColumn('distributors', 'points_redeemed')) {
                $table->unsignedInteger('points_redeemed')->default(0);
            }
        });
    }


    public function down(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            $table->dropColumn(['points_balance', 'points_redeemed']);
        });
    }
};