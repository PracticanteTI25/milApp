<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributorMonthlyGoal extends Model
{
    protected $fillable = [
        'distributor_id',
        'year',
        'month',
        'goal_amount',
    ];

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }
}
