<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');                 // ejemplo: DOYPACK MASCARILLA...
            $table->string('slug')->unique();       // doypack-mascarilla-herbal-100gr
            $table->text('description')->nullable();
            $table->string('image_path')->nullable(); // /storage/products/xxx.png

            $table->boolean('active')->default(true);

            // Opcional para futuro (stock)
            $table->unsignedInteger('stock')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};