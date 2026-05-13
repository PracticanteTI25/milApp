<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('point_settings', function (Blueprint $table) {
            $table->id();

            // Meses después de los cuales vencen los puntos
            $table->unsignedTinyInteger('expiration_months')
                ->comment('Cantidad de meses después de los cuales vencen los puntos');

            $table->timestamps();
        });

        // Registro inicial (ejemplo: 12 meses)
        DB::table('point_settings')->insert([
            'expiration_months' => 12,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('point_settings');
    }
};
