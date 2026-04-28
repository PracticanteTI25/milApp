<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KardexPuntos extends Model
{
    protected $table = 'kardex_puntos';

    protected $fillable = [
        'distributor_id',
        'bolsa_id',
        'tipo',
        'puntos',
        'descripcion',
        'fecha',
    ];
}
