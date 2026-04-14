<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Si la tabla ya existe, no hacemos nada
        if (Schema::hasTable('point_movements')) {
            return;
        }

        Schema::create('point_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('distributor_id')
                ->constrained('distributors')
                ->cascadeOnDelete();

            $table->integer('delta');

            $table->unsignedInteger('balance_after');

            $table->string('type', 30);

            $table->string('comment')->nullable();

            $table->foreignId('created_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->unsignedBigInteger('order_id')->nullable();

            $table->timestamps();

            $table->index(['distributor_id', 'created_at']);
            $table->index(['type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_movements');
    }
};