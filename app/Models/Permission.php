<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'slug', 'module_id'];

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'role_permission',   // ✅ pivote correcto
            'permission_id',
            'role_id'
        );
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}