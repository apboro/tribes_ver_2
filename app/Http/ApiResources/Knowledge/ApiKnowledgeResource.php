<?php


namespace App\Http\ApiResources\Knowledge;


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
            'uri_hash'                  => $this->resource->uri_hash,
            'updated_at'                => $this->resource->updated_at,
            'questions_count'           => $this->questions->count()
        ];
    }
}