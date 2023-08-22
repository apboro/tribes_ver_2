<?php

namespace App\Repositories\Statistic;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TelegramStatisticRepository
{
    const DAY = 'day';
    const WEEK = 'week';
    const MONTH = 'month';
    const YEAR = 'year';

    public function getStartDate($value): ?Carbon
    {
        switch ($value) {
            case self::DAY:
                return $this->getEndDate()->startOfDay();
            case self::MONTH:
                return $this->getEndDate()->startOfMonth();
            case self::YEAR:
                return $this->getEndDate()->startOfYear();
            case self::WEEK:
                return $this->getEndDate()->startOfWeek();
        }
        return null;
    }

    public function getEndDate(): Carbon
    {
        return Carbon::now();
    }
}
