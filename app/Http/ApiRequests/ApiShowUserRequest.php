<?php

namespace App\Http\ApiRequests;

/**
 * @OA\Get(path="/api/v3/user",
 *     tags={"User"},
 *     summary="Show user info",
 *     operationId="UserInfo",
 *     security={{"sanctum": {} }},
 *
 *     @OA\Response(response=200, description="Login success", @OA\JsonContent(
 *            @OA\Property(property="data", type="array",
 *                @OA\Items(
 *                    @OA\Schema (ref="#/components/schemas/userResource"),
 *                ),
 *                example={"id": 1, "name": "Jessica Smith", "email": "js@mama.com"}
 *            ),
 *            @OA\Property(property="message", type="string", nullable=true),
 *            @OA\Property(property="payload", type="array", @OA\Items(), example={}))
 *     ),
 *     @OA\Response(response=401, description="User not authorized", @OA\JsonContent(ref="#/components/schemas/api_response_unauthorized")),
 *
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *
 *     @OA\Response(response=500, description="Server Error", @OA\JsonContent(ref="#/components/schemas/api_response_server_error")),
 * )
 */
class ApiShowUserRequest extends ApiRequest
{

}