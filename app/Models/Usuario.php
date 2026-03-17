<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'nombre',
        'apellido',
        'correo',
        'password',
        'rol'
    ];

    // Ocultar contraseña en respuestas
    protected $hidden = [
        'password'
    ];
}
