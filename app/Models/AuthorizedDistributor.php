<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorizedDistributor extends Model
{
    protected $fillable = ['email', 'document', 'active'];

    public function distributor()
    {
        return $this->belongsTo(\App\Models\Distributor::class, 'document', 'document');
    }
}
