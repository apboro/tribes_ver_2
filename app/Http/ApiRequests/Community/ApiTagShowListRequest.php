<?php

namespace App\Http\ApiRequests\Community;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(path="/api/v3/chats/tags",
 *     tags={"Chats Tags"},
 *     summary="Get all chats tags",
 *     operationId="AllChatsTags",
 *     security={{"sanctum": {} }},
 *
 *     @OA\Response(response=200, description="OK"),
 * )
 */

class ApiTagShowListRequest extends ApiRequest
{

}
