<?php

namespace App\Http\ApiResources;

use App\Models\CommunityRule;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiCommunityRuleResource extends JsonResource
{
    /**
     * @var CommunityRule
     */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'user_id' => $this->resource->user_id,
            'name' => $this->resource->name,
            'content' => $this->resource->content,
            'warning' => $this->resource->warning,
            'max_violation_times' => $this->resource->max_violation_times,
            'action' => $this->resource->action,
            'warning_file' => $this->resource->warning_image_path,
            'communities' => $this->resource->communities,
            'restricted_words' => $this->resource->restrictedWords
        ];
    }

}
