<?php

namespace App\Http\Resources\Statistic;

use App\Http\Resources\ApiResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

/** @property LengthAwarePaginator $resource */
class TelegramMessages extends ApiResourceCollection
{
    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->collection = $this->collection->map(function ($item) {
            return new TelegramMessageResource($item);
        });
    }
}