<?php

namespace App\Services;

use Carbon\Carbon;

class BusinessCalendarService
{
    /**
     * Primer día hábil (lunes–viernes) del mes
     */
    public function firstBusinessDayOfMonth(Carbon $date): Carbon
    {
        $day = $date->copy()->startOfMonth();

        while ($day->isWeekend()) {
            $day->addDay();
        }

        return $day;
    }
}