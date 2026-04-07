<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Distributor extends Authenticatable
{
    use Notifiable;

    protected $table = 'distributors';

    /**
     * Campos asignables en create/update
     */
    protected $fillable = [
        'name',
        'document',
        'email',
        'password',
        'active',
        'last_login_at',
    ];

    /**
     * Campos ocultos en arrays / JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts recomendados
     */
    protected $casts = [
        'active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Dirección principal de envío
     */
    public function address()
    {
        return $this->hasOne(DistributorAddress::class)
            ->where('is_default', true);
    }
}