<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('distributor_monthly_goals', function (Blueprint $table) {

            // Ventas reales del mes
            $table->decimal('achieved_amount', 15, 2)
                ->default(0)
                ->after('goal_amount');
        });
    }

    public function down(): void
    {
        Schema::table('distributor_monthly_goals', function (Blueprint $table) {
            $table->dropColumn('achieved_amount');
        });
    }
};
