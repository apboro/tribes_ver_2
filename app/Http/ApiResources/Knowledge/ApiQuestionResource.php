<?php


namespace App\Http\ApiResources\Knowledge;

use App\Models\Knowledge\Question;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiQuestionResource extends JsonResource
{
    /**
     * @var Question
     */
    public $resource;

    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'                        => $this->resource->id,
            'status'                    => $this->resource->status,
            'knowledge_id'              => $this->resource->knowledge_id,
            'category_id'               => $this->resource->category_id,
            'category_name'             => $this->resource->getCategoryName($this->resource->category_id),
            'context'                   => $this->resource->context,
            'author_id'                 => $this->resource->author_id,
            'answer'                    => $this->resource->answer,
            'image'                     => $this->resource->image,
        ];
    }
}
