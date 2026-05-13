<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('distributor_monthly_goals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('distributor_id')
                ->constrained('distributors')
                ->cascadeOnDelete();

            // Periodo de la meta
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month'); // 1–12

            // Valor de la meta (en dinero)
            $table->decimal('goal_amount', 15, 2);

            // Auditoría básica
            $table->timestamps();

            // Una sola meta por distribuidora y mes
            $table->unique(['distributor_id', 'year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distributor_monthly_goals');
    }
};
