<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductPointPrice;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_path',
        'presentation',
        'stock',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'stock' => 'integer',
    ];

    // Historial completo de precios
    public function pointPrices()
    {
        return $this->hasMany(ProductPointPrice::class);
    }

    // Precio vigente (el que se muestra en catálogo)
    public function currentPrice()
    {
        return $this->hasOne(ProductPointPrice::class)
            ->whereNull('ends_at')
            ->latest('starts_at');
    }
}