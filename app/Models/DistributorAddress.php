<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributorAddress extends Model
{
    protected $fillable = [
        'distributor_id',
        'country',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'phone',
        'is_default',
    ];

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }
}
