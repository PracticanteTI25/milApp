<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'image_path', 'active', 'stock'];

    public function pointPrices()
    {
        return $this->hasMany(ProductPointPrice::class);
    }

    // Precio vigente (para mostrar en catálogo)
    public function currentPrice()
    {
        return $this->hasOne(ProductPointPrice::class)
            ->whereNull('ends_at')
            ->latest('starts_at');
    }
}