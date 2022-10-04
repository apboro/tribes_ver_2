<?php

namespace App\Filters\API;

use App\Exceptions\StatisticException;
use App\Helper\ArrayHelper;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class FinanceChartFilter extends QueryAPIFilter
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

    /** @var Builder */
    protected $builder;

    protected function _sortingName($name): string
    {
        $list = [
            'first_name' => 'telegram_users.first_name',//Имя подписчика
            'user_name' => 'telegram_users.user_name',//Никнейм - формат @vasyan
            'amount' => 'payments.amount',//Сумма
            'payable_type' => 'payments.payable_type',//Тип транзакции
            'create_date' => 'payments.created_at',
            'update_date' => 'payments.updated_at',
        ];
        return $list[$name] ?? $list['create_date'];
    }

    public function sort(array $data)
    {
        return $this->builder;
    }

    public function period($value)
    {
        if ($date = $this->getStartDate($value)) {
            return $this->builder
                ->where('payments.created_at', '>', $date);
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
                    return "1 hour";//час
                case self::WEEK:
                    return "1 day";//день
                case self::MONTH:
                    return "1 day";//день
                case self::YEAR:
                    return "1 month";//в среднем месяц
            }
            throw new StatisticException('Не определен период времени для значения фильтра', [
                'period' => $value,
            ]);
        }
        return 3600;
    }

}