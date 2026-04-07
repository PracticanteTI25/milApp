<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('product_point_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->unsignedInteger('points');     // ej: 892
            $table->timestamp('starts_at');        // desde cuándo aplica
            $table->timestamp('ends_at')->nullable(); // hasta cuándo (null = vigente)

            $table->timestamps();

            $table->index(['product_id', 'starts_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_point_prices');
    }
};
