<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('redencion_productos', function (Blueprint $table) {
            if (Schema::hasColumn('redencion_productos', 'puntos_unitarios')) {
                $table->dropColumn('puntos_unitarios');
            }

            if (Schema::hasColumn('redencion_productos', 'puntos_total')) {
                $table->dropColumn('puntos_total');
            }
        });
    }

    public function down(): void
    {
        Schema::table('redencion_productos', function (Blueprint $table) {
            $table->integer('puntos_unitarios')->nullable();
            $table->integer('puntos_total')->nullable();
        });
    }
};
