<?php

namespace App\Http\ApiRequests\Webinars;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\GET(
 *  path="/api/v3/webinars",
 *  operationId="webinars-show-list",
 *  summary="Show list webinar",
 *  security={{"sanctum": {} }},
 *  tags={"Webinars"},
 *     @OA\Parameter(name="offset",in="query",description="Begin records from number {offset}",required=false,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="limit",in="query",description="Total records to display",required=false,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="type",in="query",description="type of webinar in {online,planned,ended}",required=false,@OA\Schema(type="string",)),
 * @OA\Response(response=200, description="OK")
 *)
 */
class ApiWebinarsListRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'type'=>'required|in:online,planned,ended'
        ];
    }
}
