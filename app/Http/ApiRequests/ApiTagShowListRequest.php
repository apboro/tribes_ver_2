<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;

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
