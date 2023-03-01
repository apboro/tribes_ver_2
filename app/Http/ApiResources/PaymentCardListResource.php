<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentCardListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return $this->resource['data'];
    }

    public static function payload(): array
    {
        return [
            'card_types' => [
                '0' => 'write-off',
                '1' => 'write-on',
                '2' => 'write-on-off',
            ],
            'statuses' => [
                'A' => 'active',
                'I' => 'inactive',
                'E' => 'expired',
                'D' => 'deactivated',
            ],
        ];
    }
}
