<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * Relación Rol ↔ Permisos usando la tabla pivote REAL de tu proyecto.
     * (Si no se especifica, Laravel busca permission_role y falla)
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permission',
            'role_id',
            'permission_id'
        );
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }
}
