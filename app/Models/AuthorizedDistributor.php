<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorizedDistributor extends Model
{
    protected $fillable = ['email', 'active'];
}
