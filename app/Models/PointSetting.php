<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointSetting extends Model
{
    protected $fillable = [
        'expiration_months',
    ];

    public static function current(): self
    {
        return self::firstOrFail();
    }
}
