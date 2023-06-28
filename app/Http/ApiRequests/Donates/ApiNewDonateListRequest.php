<?php

namespace App\Http\ApiRequests\Donates;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *  path="/api/v3/donates",
 *  operationId="donates-list",
 *  summary="List of donates",
 *  security={{"sanctum": {} }},
 *  tags={"Donates"},
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiNewDonateListRequest extends ApiRequest
{


}