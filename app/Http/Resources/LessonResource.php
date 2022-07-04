<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'status' => 'ok',
            'id' => $this->id,
            'active' => $this->active ?? 0,
            'isPublish' => $this->isPublish ?? 0,
            'course_id' => $this->course_id,
            'lesson_meta' => [
                'title' => $this->title,
                'active' => $this->isPublished,
            ],
            'modules' => ModuleResource::collection($this->modules()->orderBy('index')->get()),
        ];
    }
}
