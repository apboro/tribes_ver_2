<?php

namespace App\Http\ApiRequests\Webinars;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\GET(
 *  path="/api/v3/visited/webinars",
 *  operationId="visited-webinars-list",
 *  summary="List visited webinars",
 *  security={{"sanctum": {} }},
 *  tags={"Webinar Visited"},
 *  @OA\Parameter(name="offset",in="query",description="Begin records from number {offset}",required=false,@OA\Schema(type="integer",)),
 *  @OA\Parameter(name="limit",in="query",description="Total records to display",required=false,@OA\Schema(type="integer",)),
 *  @OA\Response(response=200, description="OK")
 *)
 */
class ApiVisitedWebinarListRequest extends ApiRequest
{

    public function prepareForValidation(): void
    {
        $this->merge([
            'offset' => $this->offset ? (int)$this->offset : 0,
            'limit' =>  $this->limit ? (int)$this->limit : 3,
        ]);
    }

    public function rules(): array
    {
        return [
            'offset' => 'integer',
            'limit' => 'integer',
        ];
    }

}
