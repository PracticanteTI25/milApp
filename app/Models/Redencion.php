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

        // SNAPSHOT DEL PEDIDO
        'document_snapshot',
        'nombre_snapshot',
        'direccion_snapshot',
        'municipio_snapshot',
        'departamento_snapshot',
        'telefono_snapshot',
    ];

    public function productos()
    {
        return $this->hasMany(RedencionProducto::class);
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    public function direccion()
    {
        return $this->belongsTo(DistributorAddress::class, 'direccion_id');
    }
}
