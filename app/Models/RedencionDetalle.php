<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedencionDetalle extends Model
{
    protected $table = 'redencion_detalle';

    public $timestamps = false; 

    protected $fillable = [
        'redencion_id',
        'bolsa_id',
        'puntos_usados',
    ];
}
