<?php

namespace App\Http\ApiRequests\Antispam;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(path="/api/v3/antispam/{uuid}",
 *  path="/api/v3/antispam/{uuid}",
 *  operationId="antispam-show",
 *  summary="Show antispam by uuid",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Antispam"},
 *     @OA\Parameter(name="uuid",in="path",required=true,@OA\Schema(type="string")),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiAntispamShowRequest extends ApiRequest
{

}
