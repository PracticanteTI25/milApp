<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('redenciones', function (Blueprint $table) {
            $table->string('document_snapshot')->nullable()->after('direccion_id');
            $table->string('nombre_snapshot')->nullable();
            $table->string('direccion_snapshot')->nullable();
            $table->string('municipio_snapshot')->nullable();
            $table->string('departamento_snapshot')->nullable();
            $table->string('telefono_snapshot')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('redenciones', function (Blueprint $table) {
            $table->dropColumn([
                'document_snapshot',
                'nombre_snapshot',
                'direccion_snapshot',
                'municipio_snapshot',
                'departamento_snapshot',
                'telefono_snapshot',
            ]);
        });
    }
};
