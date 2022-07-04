<?php

namespace App\Http\Resources;

use App\Models\File;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonForCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $img = File::find($this->preview);
        return [
            'id' => $this->id,
            'course_id' => $this->course_id,
            'title' => $this->title,
            'preview' => ($img) ? $img->url : ''
        ];
    }
}
