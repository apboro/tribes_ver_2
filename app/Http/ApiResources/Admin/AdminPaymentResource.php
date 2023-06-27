<?php

namespace App\Http\ApiResources\Admin;

use App\Models\Payment;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class AdminPaymentResource extends JsonResource
{
    /** @var Payment */
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
            'order_id' => $this->resource->OrderId ?? null,
            'community' => $this->resource->community->title ?? null,
            'add_balance' => $this->resource->add_balance ?? null,
            'from' => $this->resource->from ?? null,
            'status' => $this->resource->status,
            'created_at' => $this->resource->created_at->timestamp,
            'user_id' => $this->resource->user_id,
            'type' => $this->resource->type ?? null,
            'telegram_id' => $this->resource->telegram_user_id,
            'telegram_user_name' => $this->resource->telegramUser ? $this->resource->telegramUser->user_name : null,
        ];
    }
}
