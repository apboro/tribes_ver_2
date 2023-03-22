<?php

namespace App\Http\ApiRequests\Authentication;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *     path="/api/v3/user/logout",
 *     operationId="logout",
 *     summary="Logout user",
 *     security={{"sanctum": {} }},
 *     tags={"Authorizathion"},
 *
 *     @OA\Response(response=200, description="Logout success", @OA\JsonContent(ref="#/components/schemas/api_response_success")),
 *
 *     @OA\Response(response=401, description="User not authorized", @OA\JsonContent(ref="#/components/schemas/api_response_unauthorized")),
 *
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *
 *     @OA\Response(response=500, description="Server Error", @OA\JsonContent(ref="#/components/schemas/api_response_server_error")),
 * )
 */
class ApiUserLogoutRequest extends ApiRequest
{

}
