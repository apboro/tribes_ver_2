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
            'name' => 'name',
            'nick_name' => 'nick_name',
            'accession_date' => 'telegram_users_community.accession_date', 
            'exit_date' => 'telegram_users_community.exit_date',
            'c_messages' => 'c_messages',
            'comm_name' => 'comm_name',
            'c_put_reactions' => 'c_put_reactions',
            'c_got_reactions' => 'c_got_reactions',
            'utility' => 'user_utility',

        ];
        return $list[$name] ?? $list['accession_date'];
    }

    public function communityId($value)
    {
        return $this->builder->where(['telegram_users_community.community_id' => $value]);
    }

    public function period($value)
    {
        if ($date = $this->getStartDate($value)) {
            return $this->builder
                ->where('telegram_users_community.accession_date', '>', $date->format('U'));
        }
    }

    public function getStartDate($value): Carbon
    {
        if (in_array($value, $this->allowedPeriods())) {
            switch ($value) {
                case self::DAY:
                    return $this->getEndDate()->sub('23 hours')->startOfHour();
                case self::WEEK:
                    return $this->getEndDate()->sub('6 days')->startOfDay();
                case self::MONTH:
                    return $this->getEndDate()->sub('30 days')->startOfDay();
                case self::YEAR:
                    return $this->getEndDate()->sub('11 months')->startOfMonth();
                    break;
            }
        }
        throw new StatisticException('Не определен период времени для значения фильтра', [
            'period' => $value,
        ]);
    }

    public function getEndDate(): Carbon
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