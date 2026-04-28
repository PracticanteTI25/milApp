<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BolsaPuntos extends Model
{
    protected $table = 'bolsas_puntos';

    protected $fillable = [
        'distributor_id',
        'mes',
        'puntos_generados',
        'puntos_disponibles',
        'estado',
        'fecha_habilitacion',
        'fecha_vencimiento',
    ];
}
