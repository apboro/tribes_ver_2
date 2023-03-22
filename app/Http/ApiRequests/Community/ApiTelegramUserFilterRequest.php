<?php

namespace App\Http\ApiRequests;
use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/user/community-users/filter",
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
