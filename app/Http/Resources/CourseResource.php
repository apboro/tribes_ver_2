<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'course_meta' => [
                'title' => $this->title,
                'cost' => $this->cost,
                'access_days' => $this->access_days,
                'payment_title' => $this->payment_title,
                'payment_description' => $this->payment_description,
                'payment_link' => $this->paymentLink(),
                'preview_link' => $this->getProductWithLesson($this->lessons->first()->id ?? 0),
                'isActive' => $this->isActive,
                'isPublished' => $this->isPublished,
                'isEthernal' => $this->isEthernal,
                'price' => $this->price,
                'preview' => $this->preview,
                'attachments' => $this->attachments,
                'thanks_text' => $this->thanks_text,
                'shipping_noty' => $this->shipping_noty,
                'activation_date' => $this->activation_date,
                'deactivation_date' => $this->deactivation_date,
                'publication_date' => $this->publication_date,
            ],
//            'lessons' => LessonForCourseResource::collection($this->lessons)
            'lessons' => LessonResource::collection($this->lessons()->orderBy('id')->get())
        ];
    }
}
