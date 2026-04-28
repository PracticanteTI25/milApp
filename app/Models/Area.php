<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Area
 *
 * Representa un área organizacional de la empresa
 * (Comercial, Marketing, etc.).
 */
class Area extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'active',
    ];

    public function users()
    {
        return $this->belongsToMany(
            \App\Models\User::class,
            'area_user'
        );
    }
}