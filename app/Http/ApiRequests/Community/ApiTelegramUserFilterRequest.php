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
 *    @OA\Parameter(
 *         name="offset",
 *         in="query",
 *         description="Begin records from number {offset}",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="limit",
 *         in="query",
 *         description="Total records to display",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="user_name",
 *         in="query",
 *         description="Telegram User user_name",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="name",
 *         in="query",
 *         description="Telegram User Name",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="accession_date_from",
 *         in="query",
 *         description="Telegram User Date accession from",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="accession_date_to",
 *         in="query",
 *         description="Telegram User Date accession to",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="community_id",
 *         in="query",
 *         description="Community ID",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="banned",
 *         in="query",
 *         description="Banned list, type = 4",
 *         required=false,
 *         @OA\Schema(
 *             type="boolean",
 *             example="false"
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="blacklisted",
 *         in="query",
 *         description="Black list, type = 1 ",
 *         required=false,
 *         @OA\Schema(
 *             type="boolean",
 *             example="false"
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="whitelisted",
 *         in="query",
 *         description="White list, type = 2",
 *         required=false,
 *         @OA\Schema(
 *             type="boolean",
 *             example="false"
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="muted",
 *         in="query",
 *         description="Mute list, type = 3",
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
