<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Collection;

/** @property Collection $resource */
class ProjectsResource extends ApiResourceCollection
{
    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->collection = $this->collection->map(function ($item){
            return new ProjectResource($item);
        });
    }

    public function toArray($request)
    {
        return parent::toArray($request);
    }

}