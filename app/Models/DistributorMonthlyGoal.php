<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributorMonthlyGoal extends Model
{
    protected $table = 'distributor_monthly_goals';

    protected $fillable = [
        'distributor_id',
        'year',
        'month',
        'goal_amount',
    ];

    /**
     * Relación con el distribuidor
     */
    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    /**
     * Relación con la venta del mismo distribuidor, año y mes
     */
    public function sale()
    {
        return $this->hasOne(\App\Models\Sale::class, 'distributor_id', 'distributor_id')
            ->whereColumn('sales.year', 'distributor_monthly_goals.year')
            ->whereColumn('sales.month', 'distributor_monthly_goals.month');
    }
}
