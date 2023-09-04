<?php

namespace App\Http\ApiRequests\Statistic;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *  path="/api/v3/statistic/publication-time",
 *  operationId="statistic-publication-time",
 *  summary="Publication statistic: save time",
 *  security={{"sanctum": {} }},
 *  tags={"Publication statistic"},
 *  @OA\Parameter(name="publication_id",in="query",description="publication id",required=true, @OA\Schema(type="integer")),
 *  @OA\Parameter(name="seconds",in="query",description="seconds",required=true, @OA\Schema(type="integer")),
 *  @OA\Response(response=200, description="OK"),
 *  @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
final class ApiPublicationViewTimeRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'publication_id' => 'required|integer',
            'seconds' => 'required|integer'
        ];
    }

}