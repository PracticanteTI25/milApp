<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('usuarios', function (Blueprint $table) {

        $table->id(); // ID interno (NO se muestra)

        $table->string('nombre');
        $table->string('apellido');

        $table->string('correo')->unique(); // seguridad: no duplicados

        $table->string('password'); // contraseña encriptada

        $table->string('rol'); // admin, marketing, etc.

        $table->timestamps();
    });
}
};
