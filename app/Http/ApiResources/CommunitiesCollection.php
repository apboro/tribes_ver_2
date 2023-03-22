<?php

namespace App\Http\ApiResources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CommunitiesCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return CommunityResource::collection($this->collection);
    }
}
