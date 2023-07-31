<?php

namespace App\Http\ApiResources;

use App\Models\Donate;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiFinancePayoutResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "paymentId" => $this->resource->paymentId,
            "amount" => $this->resource->amount,
            "card" => $this->resource->card,
            "date" => $this->resource->created_at,
        ];
    }
}
