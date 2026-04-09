<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointMovement extends Model
{
    protected $table = 'point_movements';

    protected $fillable = [
        'distributor_id',  
        'delta',  
        'balance_after',
        'type',
        'comment',
        'created_by_user_id',
        'order_id',
    ];

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }
}