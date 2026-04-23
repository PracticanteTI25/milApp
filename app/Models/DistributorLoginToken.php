<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributorLoginToken extends Model
{
    protected $fillable = [
        'email',
        'token_hash',
        'expires_at',
        'used_at',
    ];

    protected $dates = ['expires_at', 'used_at'];
}