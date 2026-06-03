<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('distributors', function (Blueprint $table) {
            $table->string('document')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('distributors', function (Blueprint $table) {
            $table->string('document')->nullable()->change();
        });
    }
};
