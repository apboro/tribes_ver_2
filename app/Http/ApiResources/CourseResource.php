<?php

namespace App\Http\ApiResources;

use App\Models\Course;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class CourseResource extends JsonResource
{

    /** @var Course */

    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     *
     * @return array|Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'cost' => $this->resource->cost,
            'access_days' => $this->resource->access_days,
            'payment_title' => $this->resource->payment_title,
            'payment_description' => $this->resource->payment_description,
            'payment_link' => $this->resource->paymentLink(),
            'preview_link' => $this->getProductWithLesson($this->lessons->first()->id ?? 0),
            'isActive' => $this->resource->isActive,
            'isPublished' => $this->resource->isPublished,
            'isEthernal' => $this->resource->isEthernal,
            'price' => $this->resource->price,
            //'preview' => $this->resource->preview,
            'attachments' => $this->resource->attachments,
            'thanks_text' => $this->resource->thanks_text,
            'shipping_noty' => $this->resource->shipping_noty,
            'activation_date' => $this->resource->activation_date,
            'deactivation_date' => $this->resource->deactivation_date,
            'publication_date' => $this->resource->publication_date
        ];
    }
}
