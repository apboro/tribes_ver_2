<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;

abstract class ApiResourceCollection extends ResourceCollection
{
    protected bool $api = false;

    public function toResponse($request): JsonResponse
    {
        //todo обработчик когда $this->resource пагинатор и когда $this->resource Collection
        if($this->api) {
            $data = array_merge([
                'items' => $this->collection->toArray(),
                'meta' => $this->expect($this->resource->toArray()),
            ],$this->with($request));
            return response()->json( $data );
        }
        return parent::toResponse($request);
    }

    public function forApi(): ApiResourceCollection
    {
        $this->api = true;
        return $this;
    }

    private function expect($paginated): array
    {
        return Arr::except($paginated, [
            'data',
            'first_page_url',
            'last_page_url',
            'prev_page_url',
            'next_page_url',
            'links',
            'path',
        ]);
    }
}