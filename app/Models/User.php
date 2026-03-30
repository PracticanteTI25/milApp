<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;
use App\Models\Area;


/**
 * Modelo User
 *
 * Representa a un usuario REAL del sistema.
 * Actualmente se autentica por base de datos.
 * En el futuro podrá mapearse a Directorio Activo.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Campos que pueden asignarse masivamente.
     *
     * IMPORTANTE:
     * - role_id y area_id se asignan SOLO desde el backend (admin).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'area_id',
    ];

    /**
     * Campos que se ocultan en respuestas (seguridad).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts automáticos de Laravel.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relación: el usuario pertenece a un rol.
     *
     * Ejemplo:
     * auth()->user()->role->slug
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relación: el usuario pertenece a un área.
     *
     * Ejemplo:
     * auth()->user()->area->name
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
