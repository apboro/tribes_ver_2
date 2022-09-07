<?php

namespace App\Http\Resources\Statistic;

use Illuminate\Http\Resources\Json\JsonResource;

class FinancesResource extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->collection = $this->collection->map(function ($item) {
            return new FinanceResource($item);
        });
    }
}
