<?php

namespace App\Http\Resources\Statistic;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ApiResourceCollection;

class TelegramMessageReactionsResours extends ApiResourceCollection
{
    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->collection = $this->collection->map(function ($item) {
            return new TelegramMessageReactionResource($item);
        });
    }
    
}
