<?php


namespace App\Http\ApiResources\Knowledge;


use App\Models\QuestionCategory;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiQuestionCategoryResource extends JsonResource
{
    /**
     * @var QuestionCategory
     */
    public $resource;

    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'             => $this->resource->id,
            'name'           => $this->resource->name,
            'knowledge_id'   => $this->resource->knowledge_id,
            'question_count' => $this->resource->questionsCount($this->resource->id),
        ];
    }
}
