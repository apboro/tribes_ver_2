<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentCardResource extends JsonResource
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
           // 'data'=>$this->resource,
            'status'=>$this->resource['status'],
            'customer_key'=>$this->resource['customer_key'],
            'message'=>$this->resource['message'],
            'details'=>$this->resource['details'],
            'redirect' => $this->resource['paymentUrl']
        ];
    }
}
