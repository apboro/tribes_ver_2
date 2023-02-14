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
            'OrderId' => $this->resource->OrderId ?? null,
            'community' => $this->resource->community->title ?? null,
            'add_balance' => $this->resource->add_balance ?? null,
            'from' => $this->resource->from ?? null,
            'status' => $this->resource->status,
            'created_at' => $this->resource->created_at,
            'user_id' => $this->resource->user_id,
            'type' => $this->resource->type ?? null,
        ];
    }
}
