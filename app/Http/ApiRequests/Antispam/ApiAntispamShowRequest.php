<?php

namespace App\Http\ApiRequests\Antispam;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(path="/api/v3/antispam/{id}",
 *  path="/api/v3/antispam/{id}",
 *  operationId="antispam-show",
 *  summary="Show antispam by id",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Antispam"},
 *     @OA\Parameter(name="id",in="path",required=true,@OA\Schema(type="integer",format="int64")),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiAntispamShowRequest extends ApiRequest
{

}
