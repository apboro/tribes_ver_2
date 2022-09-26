<?php

namespace App\Http\Resources\Manager;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UsersResource extends ResourceCollection
{

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->collection = $this->collection->map(function ($item) {
            return new UserResource($item);
        });
    }

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}