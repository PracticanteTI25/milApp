<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // distributors
        Schema::table('distributors', function (Blueprint $table) {
            if (!Schema::hasColumn('distributors', 'external_id')) {
                $table->string('external_id')
                    ->nullable()
                    ->unique()
                    ->after('id');
            }
        });

        // distributor_addresses
        Schema::table('distributor_addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('distributor_addresses', 'external_id')) {
                $table->string('external_id')
                    ->nullable()
                    ->unique()
                    ->after('distributor_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            if (Schema::hasColumn('distributors', 'external_id')) {
                $table->dropUnique(['external_id']);
                $table->dropColumn('external_id');
            }
        });

        Schema::table('distributor_addresses', function (Blueprint $table) {
            if (Schema::hasColumn('distributor_addresses', 'external_id')) {
                $table->dropUnique(['external_id']);
                $table->dropColumn('external_id');
            }
        });
    }
};