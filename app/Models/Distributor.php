<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\DistributorResetPasswordNotification;
use Illuminate\Contracts\Auth\CanResetPassword;

class Distributor extends Authenticatable implements CanResetPassword
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

    // Historial completo de movimientos (extracto)
    public function pointMovements()
    {
        return $this->hasMany(PointMovement::class)->orderByDesc('created_at');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new DistributorResetPasswordNotification($token));
    }

}