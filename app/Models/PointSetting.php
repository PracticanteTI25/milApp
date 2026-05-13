<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointSetting extends Model
{
    protected $fillable = [
        'expiration_months',
    ];

    /**
     * Siempre trabajamos con un solo registro (configuración global)
     */
    public static function current(): self
    {
        return self::firstOrFail();
    }
}
