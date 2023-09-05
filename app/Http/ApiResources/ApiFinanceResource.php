<?php

namespace App\Http\ApiResources;

use App\Models\Donate;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiFinanceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->resource->id,
            "amount" => $this->resource->amount,
            "type" => $this->getTypeName($this->resource->type),
            "buy_date" => $this->resource->buy_date,
            "payable_title" => $this->resource->payable->title ?? $this->resource->payable->description ?? '—',
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
            "publication" => 'Публикация',
            "webinar" => 'Вебинар',
            "payout" => 'Вывод средств',
        ];
        return $list[$code]?? 'Не определено';
    }
}
