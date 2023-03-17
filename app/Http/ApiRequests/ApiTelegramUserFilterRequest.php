<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Post(
 *  path="/api/v3/user/community-users/filter",
 *  operationId="community-users-filter",
 *  summary="Filter community users",
 *  security={{"sanctum": {} }},
 *  tags={"Chats Users"},
 *   @OA\RequestBody(
 *     @OA\JsonContent(
 *       @OA\Property(property="name", type="string", example="Alex"),
 *       @OA\Property(property="accession_date_from", type="string", example="2023-01-01"),
 *       @OA\Property(property="accession_date_to", type="string", example="2023-01-01"),
 *       @OA\Property(property="community_id", type="integer", example=2),
 *     )
 *   ),
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
