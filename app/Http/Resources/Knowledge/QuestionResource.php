<?php

namespace App\Http\Resources\Knowledge;

use App\Http\Resources\CommunityResource;
use App\Models\Knowledge\Question;
use Illuminate\Http\Resources\Json\JsonResource;

/** @property Question $resource */
class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->resource->id,
            "community_id" => $this->resource->community_id,
            "author_id" => $this->resource->author_id,
            //"analog_uuid" => "6EHdyqwOfEMDjAM9xEvTiM0Ui7NvpHiJ",
            "link" => $this->resource->getLink(),
            "is_draft" => (bool)$this->resource->is_draft,
            "is_public" => (bool)$this->resource->is_public,
            "c_enquiry" => $this->resource->c_enquiry,
            "context" => $this->resource->context ?? '',
            "created_at" => $this->resource->created_at,
            "updated_at" => $this->resource->updated_at,
            'community' => CommunityResource::make($this->whenLoaded('community')),
            'answer' => AnswerResource::make($this->whenLoaded('answer')),
            'public_link' => $this->resource->getPublicLink()
        ];
    }
}
