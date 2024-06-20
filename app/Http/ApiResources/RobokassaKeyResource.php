<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Resources\Json\JsonResource;

class RobokassaKeyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'shop_id' => $this->shop_id,
            'merchant_login' => $this->merchant_login,
            'first_password' => $this->first_password,
            'second_password' => $this->second_password,
        ];
    }
}
