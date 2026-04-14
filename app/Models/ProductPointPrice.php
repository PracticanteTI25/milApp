<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPointPrice extends Model
{
    protected $fillable = [
        'product_id',
        'points',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'points' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}