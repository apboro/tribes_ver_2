<?php

namespace App\Http\ApiResources\Rules;

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
            'uuid' => $this->resource->uuid,
            'user_id' => $this->resource->user_id,
            'name' => $this->resource->name,
            'content' => $this->resource->content,
            'content_image' => $this->resource->content_image_path,
            'warning' => $this->resource->warning,
            'max_violation_times' => $this->resource->max_violation_times,
            'action' => $this->resource->action,
            'warning_image' => $this->resource->warning_image_path,
            'user_complaint_image' => $this->resource->user_complaint_image_path,
            'communities' => $this->resource->communities,
            'restricted_words' => $this->resource->restrictedWords->pluck('word'),
            'complaint_text' => $this->resource->complaint_text,
            'quiet_on_restricted_words' => $this->resource->quiet_on_restricted_words,
            'quiet_on_complaint' => $this->resource->quiet_on_complaint,
        ];
    }

}
