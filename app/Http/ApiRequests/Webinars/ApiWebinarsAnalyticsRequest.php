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
 * @OA\Parameter(
 *          name="period",
 *          in="query",
 *          description="day, week, month, year",
 *          required=false,
 *          @OA\Schema(
 *              type="string",
 *              example="day"
 *          )
 *      ),
 * @OA\Response(response=200, description="OK"),
 *
 *)
 */
class ApiWebinarsAnalyticsRequest extends ApiRequest
{
    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            'period' => 'string|in:day,week,month,year',
        ];
    }

}
