<?php

namespace App\Helper;

use Illuminate\Support\Carbon;

class QueryHelper
{
    public const START_DATA_PERIOD = 'start';
    public const END_DATA_PERIOD = 'end';

    /**
     * get current period start end data
     *
     * @param string $criteria
     *
     * @return array
     */
    public static function buildPeriodDates(string $criteria): array
    {
        $now = Carbon::now();

        switch ($criteria) {
            case 'week':
                $start  = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                break;
            case 'month':
                $start  = $now->copy()->firstOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
            case 'year':
                $start  = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                break;
            default: // day
                $start = $now;
                $end = $now;
        }

        return [
            self::START_DATA_PERIOD => $start->format('Y-m-d 00:00:00'),
            self::END_DATA_PERIOD => $end->format('Y-m-d 23:59:59'),
        ];
    }

    public static function prepareWhereInListToStringParameter(array $whereIn): string
    {
        if(empty($whereIn)) {
            return '(null)';
        }

        $whereInParams = '(';
        foreach ($whereIn as $param) {
            if($param || $param === 0) {
                $whereInParams .= $param . ',';
            }else{
                $whereInParams .= NULL . ',';
            }
        }
        $whereInParams .= ')';

        return str_replace(',)' , ')', $whereInParams );
    }

}