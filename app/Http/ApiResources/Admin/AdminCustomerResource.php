<?php

namespace App\Http\ApiResources\Admin;

use App\Models\Payment;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class AdminCustomerResource extends JsonResource
{

    /**
     * @var Payment
     */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array|Arrayable|JsonSerializable
     */

    public function toArray($request)
    {
        return [
            'id' => $this->resource->user_id,
            'name' => $this->resource->from,
        ];
    }
}
