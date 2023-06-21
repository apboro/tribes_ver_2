<?php

namespace App\Http\ApiResources\Admin;

use App\Models\Community;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class AdminCommunityResource extends JsonResource
{
    /** @var Community */
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
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'owner_name' => $this->resource->communityOwner->name ?? null,
            'owner_user_name' => $this->resource->communityOwner->telegramMeta->pluck('user_name'),
            'owner_id' => $this->resource->communityOwner->id ?? null,
            'telegram' => $this->resource->connection->chat_type ?? null,
            'created_at' => $this->resource->created_at->timestamp,
            'followers' => $this->resource->followers_count,
            'balance' => $this->resource->balance,
            'chat_invite_link' => $this->resource->connection->chat_invite_link ?? null,
        ];
    }
}
