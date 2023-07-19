<?php

namespace App\Http\ApiRequests\Statistic;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(path="/api/v3/statistic/export-payments",
 *     tags={"Statistic Payments"},
 *     summary="Payments Export",
 *     operationId="payments-export",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(
 *         name="type",
 *         in="query",
 *         description="type of output format",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *             example="csv"
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="period",
 *         in="query",
 *         description="day, week, month, year",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *             example="day"
 *         )
 *     ),
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiExportFinancesRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'period' => 'string|in:day,week,month,year',
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'filter'=> [
                'period' => $this->request->get('period'),
            ]
        ]);
    }


}