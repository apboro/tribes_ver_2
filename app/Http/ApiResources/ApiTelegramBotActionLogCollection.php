<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiTelegramBotActionLogCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return ApiTelegramBotActionLogResource::collection($this->collection)->toArray($request);
    }
}
