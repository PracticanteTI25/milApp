<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Agregar solo si no existe
            if (!Schema::hasColumn('products', 'presentation')) {
                $table->string('presentation')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'presentation')) {
                $table->dropColumn('presentation');
            }
        });
    }
};
