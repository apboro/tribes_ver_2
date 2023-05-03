<?php


namespace App\Http\ApiResources;

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
            'knowledge_id'                    => $this->resource->knowledge_id,
            'category_id'                    => $this->resource->category_id,
            'overlap'                    => $this->resource->overlap,
            'context'                    => $this->resource->context,
            'author_id'                    => $this->resource->author_id,
            'uri_hash'                    => $this->resource->uri_hash,
            'c_enquiry'                    => $this->resource->c_enquiry,
            'answer'                    => $this->resource->answer,
        ];
    }
}