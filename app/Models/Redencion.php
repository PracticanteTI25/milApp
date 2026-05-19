<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redencion extends Model
{
    protected $table = 'redenciones';

    protected $fillable = [
        'distributor_id',
        'direccion_id',
        'fecha',
        'total_puntos_usados',
    ];

    /**
     * Distribuidor que realizó la redención
     */
    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    /**
     * Dirección asociada a la redención
     */
    public function direccion()
    {
        return $this->belongsTo(DistributorAddress::class);
    }

    /**
     * Productos canjeados en la redención
     */
    public function productos()
    {
        return $this->hasMany(RedencionProducto::class);
    }
}
