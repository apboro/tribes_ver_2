<?php

namespace App\Http\Resources\Manager;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'OrderId' => $this->resource->OrderId,
            'community' => $this->resource->community->title,
            'add_balance' => $this->resource->add_balance,
            'from' => $this->resource->from,
            'status' => $this->resource->status,
            'created_at' => $this->resource->created_at,
            'type' => $this->resource->type,
        ];
    }
}
