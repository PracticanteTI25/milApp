<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table = 'devoluciones';

    protected $fillable = [
        'distributor_id',
        'lote',
        'cantidad',
        'imagen_path',
        'observaciones',
        'estado',
    ];

    // Relación con distribuidor
    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }
}
