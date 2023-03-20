<?php

namespace App\Http\ApiRequests;

use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *  path="/api/v3/user/community-users",
 *  operationId="community-users-list",
 *  summary="Get paginated list of all communities owner users",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Users"},
 *  @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Page of list",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *      @OA\Response(response=200, description="OK")
 *
 *)
 */


class ApiCommunityTelegramUserListRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'page' => 'required|integer'
        ];
    }

}
