<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    protected $table = 'metas';

    protected $fillable = [
        'distributor_id',
        'mes',
        'meta_monto',
        'monto_logrado',
        'cumplida',
        'fecha_cumplimiento',
    ];
}