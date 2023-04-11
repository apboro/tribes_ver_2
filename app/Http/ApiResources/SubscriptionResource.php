<?php

namespace App\Http\ApiResources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    /**
     * @OA\Schema(
     *     title="subscription",
     *     schema="subscriptionResource",
     *     description="Subscription resource",
     *     @OA\Xml(name="subscriptionResource"),
     *       @OA\Property(
     *            property="id",
     *            description="Subscription ID",
     *            type="integer",
     *        ),
     *        @OA\Property(
     *            property="name",
     *            description="Название подписки",
     *            type="string",
     *       ),
     *        @OA\Property(
     *            property="description",
     *            description="Описание подписки",
     *            type="string",
     *       ),
     *        @OA\Property(
     *            property="price",
     *            description="Стоимость подписки",
     *            type="integer",
     *       ),
     *        @OA\Property(
     *            property="period_days",
     *            description="Срок подписки",
     *            type="integer",
     *       ),
     *       @OA\Property(
     *            property="image_url",
     *            description="Картинка подписки",
     *            type="string",
     *       ),
     *  )
     */

    public function toArray($request)
    {
        return [
            "user_subscription_id" => $this->resource->id,
            "user_id" => $this->resource->user_id,
            "subscription_id" => $this->resource->subscription_id,
            "created_at" => $this->resource->created_at->timestamp,
            "updated_at" => $this->resource->updated_at->timestamp,
            "isRecurrent" => $this->resource->isRecurrent,
            "isActive" => $this->resource->isActive,
            "expiration_date" =>Carbon::parse($this->resource->expiration_date)->timestamp,
        ];
    }
}
