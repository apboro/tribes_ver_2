<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TelegramAccountCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return TelegramAccountResource::collection($this->collection);
    }
}
