<?php

namespace App\Filters\API;

use App\Exceptions\StatisticException;
use App\Helper\ArrayHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;


class TeleMessagesFilter extends QueryAPIFilter
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
            'message_date' => 'message_date',
            'text' => 'text',
            'name' => 'name',
            'nick_name' => 'nick_name',
            'answers' => 'answers',
            'utility' => 'utility',
            'count_reactions' => 'count_reactions'
        ];
        return $list[$name] ?? $list['message_date'];
    }

    public function period($value)
    {
        if ($date = $this->getStartDate($value)) {
            return $this->builder
                ->where('message_date', '>', $date->format('U'));
        }
    }

    public function getStartDate($value): Carbon
    {
        if (in_array($value, $this->allowedPeriods())) {
            switch ($value) {
                case self::DAY:
                    return $this->getEndDate()->subDay();
                case self::WEEK:
                    return $this->getEndDate()->subWeek();
                case self::MONTH:
                    return $this->getEndDate()->subMonth();
                case self::YEAR:
                    return $this->getEndDate()->subYear();
                    break;
            }
        }
        throw new StatisticException('Не определен период времени для значения фильтра', [
            'period' => $value,
        ]);
    }

    protected function getEndDate(): Carbon
    {
        return Carbon::now();
    }

    public function getScale()
    {
        $value = $this->filters()['period'] ?? 'day';
        if (in_array($value, $this->allowedPeriods())) {
            switch ($value) {
                case self::DAY:
                    return 3600;//час
                case self::WEEK:
                    return 3600 * 24;//день
                case self::MONTH:
                    return 3600 * 24;//день
                case self::YEAR:
                    return 2628000;//в среднем месяц
            }
            throw new StatisticException('Не определен период времени для значения фильтра', [
                'period' => $value,
            ]);
        }
        return 3600;
    }
}