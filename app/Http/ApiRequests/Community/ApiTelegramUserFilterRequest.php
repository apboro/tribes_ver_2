<?php

namespace App\Http\ApiRequests\Community;

use App\Http\ApiRequests\ApiRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\GET(
 *  path="/api/v3/user/community-users",
 *  operationId="community-users-filter",
 *  summary="Filter community users",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Users"},
 *   @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Records per page",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *             example=20
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Page number",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *             example=3
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="user_name",
 *         in="query",
 *         description="Telegram User user_name",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             example="big_daddy18"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="name",
 *         in="query",
 *         description="Telegram User Name",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             example="Иван Воронин"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="accession_date_from",
 *         in="query",
 *         description="Telegram User Date accession from",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             example="2023-01-01"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="accession_date_to",
 *         in="query",
 *         description="Telegram User Date accession to",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             example="2023-01-01"
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="community_id",
 *         in="query",
 *         description="Community ID",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *             example="12"
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="banned",
 *         in="query",
 *         description="Banned list",
 *         required=false,
 *         @OA\Schema(
 *             type="boolean",
 *             example="false"
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="blacklisted",
 *         in="query",
 *         description="Black list",
 *         required=false,
 *         @OA\Schema(
 *             type="boolean",
 *             example="false"
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="whitelisted",
 *         in="query",
 *         description="White list",
 *         required=false,
 *         @OA\Schema(
 *             type="boolean",
 *             example="false"
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="muted",
 *         in="query",
 *         description="Mute list",
 *         required=false,
 *         @OA\Schema(
 *             type="boolean",
 *             example="false"
 *         )
 *     ),
 *      @OA\Response(response=200, description="OK")
 *)
 */
class ApiTelegramUserFilterRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'name' => 'string',
            'user_name' => 'string',
            'accession_date_from' => 'date_format:Y-m-d|nullable',
            'accession_date_to' => 'date_format:Y-m-d|nullable',
            'community_id' => 'integer|min:0',
            'banned' => 'string',
            'muted' => 'string',
            'whitelisted' => 'string',
            'blacklisted' => 'string',
            'is_spammer' => 'integer'

        ];
    }


}
