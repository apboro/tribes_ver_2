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
            "payable_type" => $this->resource->payable_type,
            "buy_date" => $this->resource->buy_date,
            "status" => $this->resource->status,
            "tele_login" => $this->resource->tele_login,
            "first_name" => $this->resource->first_name,
        ];
    }
}
