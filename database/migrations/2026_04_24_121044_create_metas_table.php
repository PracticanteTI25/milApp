<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('metas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('distributor_id')
                ->constrained('distributors');

            $table->date('mes'); // YYYY-MM-01

            $table->decimal('meta_monto', 12, 2);
            $table->decimal('monto_logrado', 12, 2)->default(0);

            $table->boolean('cumplida')->default(false);
            $table->timestamp('fecha_cumplimiento')->nullable();

            $table->timestamps();

            $table->unique(['distributor_id', 'mes']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metas');
    }
};
