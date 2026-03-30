<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Role
 *
 * Representa un rol técnico del sistema (admin_sistema, consulta, etc.).
 * Los permisos se asignan a los roles, no directamente a los usuarios.
 */
class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'active',
    ];
}