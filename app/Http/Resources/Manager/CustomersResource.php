<?php

namespace App\Http\Resources\Manager;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomersResource extends ResourceCollection
{
    public static $wrap = 'customers';

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->collection = $this->collection->map(function ($item){
            return new CustomerResource($item);
        });
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
