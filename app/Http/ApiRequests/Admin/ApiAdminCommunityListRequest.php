<?php

namespace App\Http\ApiRequests\Admin;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;


/**
 * @OA\Get(path="/api/v3/manager/communities",
 *     tags={"Admin chats"},
 *     summary="Show chats",
 *     operationId="admin-chat-list",
 *     security={{"sanctum": {} }},
 *     @OA\Parameter(name="offset",in="query",description="Begin records from number {offset}",required=false,@OA\Schema(type="integer",)),
 *     @OA\Parameter(name="limit",in="query",description="Total records to display",required=false,@OA\Schema(type="integer",)),
 *     @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 * )
 */
class ApiAdminCommunityListRequest extends ApiRequest
{

}
