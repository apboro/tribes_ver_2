<?php

namespace App\Http\ApiRequests\Community;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(path="/api/v3/user/chats",
 *     tags={"Chats"},
 *     summary="Show chats",
 *     operationId="ChatsListInfo",
 *     security={{"sanctum": {} }},
 *
 *     @OA\Response(response=200, description="OK"),
 * )
 */

class ApiCommunityListRequest extends ApiRequest
{


}
