<?php

namespace App\Http\ApiRequests\Profile;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Get(path="/api/v3/manager/users",
 *     tags={"Admin users"},
 *     summary="Show user list",
 *     operationId="admin-user-list",
 *     security={{"sanctum": {} }},
 *
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiUserListManagerRequest extends ApiRequest
{

}
