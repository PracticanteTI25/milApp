<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('distributor_monthly_goals', function (Blueprint $table) {
            if (Schema::hasColumn('distributor_monthly_goals', 'achieved_amount')) {
                $table->dropColumn('achieved_amount');
            }
        });
    }

    public function down()
    {
        Schema::table('distributor_monthly_goals', function (Blueprint $table) {
            $table->decimal('achieved_amount', 14, 2)->default(0);
        });
    }
};
