<?php

namespace App\Http\Resources\Statistic;

use App\Repositories\Statistic\DTO\ChartData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

/** @property ChartData $resource */
class MemberChartsResource extends JsonResource
{

    public function toArray($request)
    {
        return $this->resource->getValues();
    }

    public function toResponse($request): JsonResponse
    {
        $data = array_merge([
            'items' => $this->resource->getValues(),
            'meta' => array_merge($this->resource->getAdditions(), [
                'marks' => $this->resource->getMarks(),
            ]),
        ]);
        return response()->json($data);
    }
}