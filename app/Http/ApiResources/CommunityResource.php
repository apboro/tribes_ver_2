<?php

namespace App\Http\ApiResources;

use App\Models\Antispam;
use App\Models\Community;
use App\Models\CommunityRule;
use App\Models\Knowledge\Knowledge;
use App\Models\Onboarding;
use App\Models\UserRule;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CommunityResource extends JsonResource
{

    /**  @var Community */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var Knowledge|null $knowledge */
        $knowledge = $this->resource->knowledge;

        return [
            "id" => $this->resource->id,
            "title" => $this->resource->title,
            "image" => $this->resource->image,
            "description" => $this->resource->description,
            "created_at" => $this->resource->created_at->timestamp,
            "updated_at" => $this->resource->updated_at->timestamp,
            "balance" => $this->resource->balance,
            "knowledge" => [
                'id' => $knowledge->id ?? null,
                'name' => $knowledge->name ?? null
            ],
            "type" => $this->resource->connection->chat_type,
            "tags" => $this->resource->tags->makeHidden('pivot'),
            "rules" => $this->resource->getCommunityRules(),
        ];
    }
}
