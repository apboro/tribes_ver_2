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
            "id" => $this->resource->id,
            "name" => $this->resource->publicName(),
            "image" => $this->resource->photo_url,
        ];
    }
}
