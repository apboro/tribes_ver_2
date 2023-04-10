<?php

namespace App\Http\ApiResources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;


class TelegramAccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    /**
     * @OA\Schema(
     *     title="Telegram Account",
     *     schema="telegramAccountResource",
     *     description="Telegram account resource",
     *     @OA\Xml(name="telegramAccountResource"),
     *       @OA\Property(
     *            property="id",
     *            description="DB record ID",
     *            type="integer",
     *        ),
     *        @OA\Property(
     *            property="name",
     *            description="User name in telegram",
     *            type="string",
     *       ),
     *        @OA\Property(
     *            property="image",
     *            description="Telegram image",
     *            type="string",
     *       ),
     *  )
     */
    public function toArray($request): array
    {
        return [
        "user_id" => $this->resource->id,
        "telegram_id" => $this->resource->telegram_id,
        "auth_date" => $this->resource->auth_date,
        "first_name" => $this->resource->first_name,
        "last_name" => $this->resource->last_name,
        "photo_url" => $this->resource->photo_url,
        "created_at" => $this->resource->created_at->timestamp,
        "user_name" => $this->resource->username,
        ];
    }
}
