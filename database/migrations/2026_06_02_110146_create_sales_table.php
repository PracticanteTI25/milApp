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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('distributor_id')->constrained()->cascadeOnDelete();

            $table->integer('year');
            $table->tinyInteger('month'); // 1–12

            $table->decimal('achieved_amount', 14, 2);

            $table->timestamps();

            // evita duplicados por mes
            $table->unique(['distributor_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
