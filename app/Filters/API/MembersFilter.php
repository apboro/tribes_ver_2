<?php

namespace App\Filters\API;

use App\Exceptions\StatisticException;
use App\Helper\ArrayHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

/**
 * @property EloquentBuilder $builder
 * @method EloquentBuilder apply(EloquentBuilder $builder)
 */
class MembersFilter extends QueryAPIFilter
{
    const DAY = 'day';
    const WEEK = 'week';
    const MONTH = 'month';
    const YEAR = 'year';
    const ALL_TIME = null;

    protected function allowedPeriods()
    {
        return [
            self::DAY => self::DAY,
            self::WEEK => self::WEEK,
            self::MONTH => self::MONTH,
            self::YEAR => self::YEAR,
        ];
    }

    protected function _sortingName($name): string
    {
        $list = [
            'accession_date' => 'telegram_users_community.accession_date',
            'exit_date' => 'telegram_users_community.exit_date',
        ];
        return $list[$name] ?? $list['accession_date'];
    }

    public function community($value)
    {
        return $this->builder->where(['telegram_users_community.community_id' => $value]);
    }

    public function period($value)
    {
        if ($date = $this->periodAsDates($value)) {
            return $this->builder
                ->where('telegram_users_community.accession_date', '>', $date->format('U'));
        }
    }

    private function periodAsDates($value): ?Carbon
    {
        if(in_array($value,$this->allowedPeriods())){
            switch ($value) {
                case self::DAY:
                    return Carbon::now()->subDay();
                case self::WEEK:
                    return Carbon::now()->subWeek();
                case self::MONTH:
                    return Carbon::now()->subMonth();
                case self::YEAR:
                    return Carbon::now()->subYear();
            }
            throw new StatisticException('Не определен период времени для значения фильтра',[
                'period' => $value
            ]);
        }
        return null;
    }
}