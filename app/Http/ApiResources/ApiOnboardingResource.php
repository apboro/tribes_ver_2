<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiOnboardingResource extends JsonResource
{
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
            'rules' => $this->resource->rules,
            'title' => $this->resource->title,
            'question_image' => $this->resource->question_image,
            'greeting' => $this->resource->greeting,
        ];

    }
}
