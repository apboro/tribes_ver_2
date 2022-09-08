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
            "add_balance" => $this->resource->add_balance,
            "payable_type" => $this->resource->payable_type,
            "buy_date" => $this->resource->buy_date,
            "status" => $this->resource->status,
            "tele_login" => $this->resource->tele_login,
            "first_name" => $this->resource->first_name,

        ];
    }

    public function toResponse($request): JsonResponse
    {
//        dd(1);
        $data = array_merge([
            'items' => $this->resource->getValues(),
            'meta' => array_merge($this->resource->getAdditions(), [
                'marks' => $this->resource->getMarks(),
            ]),
        ]);
        return response()->json($data);
    }
}
