<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'status'=>$this->resource['status'],
            'customer_key'=>$this->resource['customer_key'],
            'message'=>$this->resource['message'],
            'details'=>$this->resource['details'],
            'redirect' => $this->resource['paymentUrl']
        ];
    }
}
