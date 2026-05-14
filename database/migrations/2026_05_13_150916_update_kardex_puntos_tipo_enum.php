<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("
            ALTER TABLE kardex_puntos
            MODIFY tipo ENUM(
                'generacion',
                'habilitacion',
                'canje',
                'ajuste'
            ) NOT NULL
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE kardex_puntos
            MODIFY tipo ENUM(
                'generacion',
                'habilitacion',
                'canje'
            ) NOT NULL
        ");
    }
};
