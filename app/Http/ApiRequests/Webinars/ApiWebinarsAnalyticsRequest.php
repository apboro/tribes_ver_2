<?php

namespace App\Http\ApiRequests\Webinars;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\GET(
 *  path="/api/v3/webinars/analytic",
 *  operationId="webinars-show-analytic",
 *  summary="Show webinar analytic",
 *  tags={"Webinars"},
 *
 * @OA\Response(response=200, description="OK")
 *)
 */
class ApiWebinarsAnalyticsRequest extends ApiRequest
{

}
