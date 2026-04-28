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
}
