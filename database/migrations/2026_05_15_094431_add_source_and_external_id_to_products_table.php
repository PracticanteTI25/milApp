<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            // Origen del producto: manual o api
            if (!Schema::hasColumn('products', 'source')) {
                $table->enum('source', ['manual', 'api'])
                    ->default('manual')
                    ->after('stock');
            }

            // Identificador externo (solo para productos de API)
            if (!Schema::hasColumn('products', 'external_id')) {
                $table->string('external_id')
                    ->nullable()
                    ->unique()
                    ->after('source');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            if (Schema::hasColumn('products', 'external_id')) {
                $table->dropColumn('external_id');
            }

            if (Schema::hasColumn('products', 'source')) {
                $table->dropColumn('source');
            }
        });
    }
};
