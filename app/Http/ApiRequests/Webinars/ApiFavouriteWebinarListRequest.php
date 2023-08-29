<?php

namespace App\Http\ApiRequests\Webinars;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\GET(
 *  path="/api/v3/webinars/favourite",
 *  operationId="favourite-webinars-list",
 *  summary="List favourite webinars",
 *  security={{"sanctum": {} }},
 *  tags={"Webinar Favourite"},
 *  @OA\Parameter(name="offset",in="query",description="Begin records from number {offset}",required=false,@OA\Schema(type="integer",)),
 *  @OA\Parameter(name="limit",in="query",description="Total records to display",required=false,@OA\Schema(type="integer",)),
 *  @OA\Response(response=200, description="OK")
 *)
 */
final class ApiFavouriteWebinarListRequest extends ApiRequest
{

}
