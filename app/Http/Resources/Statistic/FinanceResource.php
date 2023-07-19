<?php

namespace App\Http\Resources\Statistic;

use App\Models\Donate;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

/** @property object $resource */
class FinanceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->resource->id,
            "amount" => $this->resource->amount/100 . ' '. Donate::$currency_labels['rub'],
            "type" => $this->getTypeName($this->resource->type),
            "buy_date" => $this->resource->buy_date,
            "payable_title" => $this->resource->payable->title ?? $this->resource->payable->description ?? '—',
            "telegram_id" => $this->resource->tele_login,
            "photo_url" => $this->resource->photo_url,
            "first_name" => $this->resource->first_name,
            "user_name" => $this->resource->user_name,
            "email" => $this->resource->email
        ];
    }

    protected function getTypeName(string $code): string
    {
        $list = [
            "donate" => 'Донат',
            "tariff" => 'Тариф',
            "course" => 'Медиатовар',
            "payout" => 'Вывод средств',
        ];
        return $list[$code]?? 'Не определено';
    }
}
