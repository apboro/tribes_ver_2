<?php

namespace App\Http\Resources\Statistic;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MediaSalesResource extends ResourceCollection
{

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->collection = $this->collection->map(function ($item){
            return new MediaSaleResource($item);
        });
    }

    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function with($request): array
    {
        return parent::toArray($request);
    }
}
