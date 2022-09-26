<?php

namespace App\Http\Resources\Statistic;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

/** @property object $resource */
class FinanceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "amount" => $this->resource->amount,
            "type" => [
                'value' => $this->resource->type,
                'name' => $this->getTypeName($this->resource->type),
            ],
            "buy_date" => $this->resource->buy_date,
            "status" => $this->resource->status,
            "tele_login" => $this->resource->tele_login,
            "first_name" => $this->resource->first_name,
        ];
    }

    protected function getTypeName(string $code): string
    {
        $list = [
            "donate" => 'Донат',
            "tariff" => 'Оплата подписки',
            "course" => 'Медиа товар',
            "payout" => 'Вывод средств',
        ];
        return $list[$code]?? 'Не определено';
    }
}
