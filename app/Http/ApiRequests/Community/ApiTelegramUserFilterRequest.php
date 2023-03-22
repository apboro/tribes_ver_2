<?php

namespace App\Http\ApiRequests\Community;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *  path="/api/v3/user/community-users",
 *  operationId="community-users-filter",
 *  summary="Filter community users",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Users"},
 *     @OA\Parameter(
 *         name="name",
 *         in="query",
 *         description="Telegram User Name",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             example="Аким"
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
 *      @OA\Response(response=200, description="OK")
 *)
 */
class ApiTelegramUserFilterRequest extends ApiRequest
{

    public function rules():array
    {
        return [
            'name'=>'string',
            'accession_date_from'=>'date_format:Y-m-d|nullable',
            'accession_date_to'=>'date_format:Y-m-d|nullable',
            'community_id'=>'integer|min:0'
        ];
    }


}
