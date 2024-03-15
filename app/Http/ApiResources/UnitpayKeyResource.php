<?php

namespace App\Http\ApiResources;

use App\Helper\PseudoCrypt;
use App\Models\Author;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitpayKeyResource extends JsonResource
{
    public $resource;

    public function toArray($request): array
    {
        return [
            'shop_id' => $this->resource->shop_id,
            'project_id' => $this->resource->project_id,
            'secretKey' => $this->resource->secretKey,
        ];
    }
}