<?php

namespace App\Filters\API;

use App\Exceptions\StatisticException;
use App\Helper\ArrayHelper;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

class FinanceFilter extends QueryAPIFilter
{
    /** @var Builder */
    protected $builder;
    protected function _sortingName($name): string
    {
        $list = [
            'first_name' => 'telegram_users.first_name',//Имя подписчика
            'user_name' => 'telegram_users.user_name',//Никнейм - формат @vasyan
            'add_balance' => 'payments.add_balance',//Сумма
            'payable_type' => 'payments.payable_type',//Тип транзакции
            'create_date' => 'payments.created_at',
            'update_date' => 'payments.updated_at',
        ];
        return $list[$name] ?? $list['create_date'];
    }
}