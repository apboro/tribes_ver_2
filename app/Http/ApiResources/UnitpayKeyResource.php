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
            'user_id' => $this->resource->user_id,
            'project_id' => $this->resource->project_id,
            'secretKey' => $this->resource->secretKey,
        ];
    }
}