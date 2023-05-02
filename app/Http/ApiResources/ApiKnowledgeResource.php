<?php


namespace App\Http\ApiResources;


use App\Models\Knowledge\Knowledge;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiKnowledgeResource extends JsonResource
{
    /**
     * @var Knowledge
     */
    public $resource;

    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'                        => $this->resource->id,
            'name'                      => $this->resource->name,
            'status'                    => $this->resource->status,
            'question_in_chat_lifetime' => $this->resource->question_in_chat_lifetime,
            'updated_at'                => $this->resource->updated_at,
            'questions_count'           => $this->questions->count()
        ];
    }
}