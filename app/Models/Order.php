<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'distributor_id',
        'total_points',
        'status',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }
}