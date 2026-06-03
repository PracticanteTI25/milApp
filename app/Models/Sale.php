<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'distributor_id',
        'year',
        'month',
        'achieved_amount'
    ];

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }
}
