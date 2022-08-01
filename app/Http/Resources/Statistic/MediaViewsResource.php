<?php

namespace App\Http\Resources\Statistic;

use App\Http\Resources\ApiResourceCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

/** @property LengthAwarePaginator $resource */
class MediaViewsResource extends ApiResourceCollection
{

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->collection = $this->collection->map(function ($item) {
            return new MediaViewResource($item);
        });
    }

}
