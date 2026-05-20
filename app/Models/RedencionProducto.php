<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedencionProducto extends Model
{
    protected $table = 'redencion_productos';

    protected $fillable = [
        'redencion_id',
        'product_id',
        'cantidad',
    ];

    public function redencion()
    {
        return $this->belongsTo(Redencion::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
