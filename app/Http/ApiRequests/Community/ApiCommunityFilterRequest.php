<?php

namespace App\Http\ApiRequests\Community;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Get(
 *  path="/api/v3/user/chats",
 *  operationId="community-filter",
 *  summary="Filter communities of auth user",
 *  security={{"sanctum": {} }},
 *  tags={"Chats"},
 *    @OA\Parameter(
 *         name="name",
 *         in="query",
 *         description="Community name",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             example="Bubbles"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="tag_name",
 *         in="query",
 *         description="Community tag name",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             example="Bubbles"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="date_from",
 *         in="query",
 *         description="Communities with date of add to Spodial from",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             example="2023-01-01"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="accession_date_to",
 *         in="query",
 *         description="Communities with date of add to Spodial to",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             example="2023-01-01"
 *         )
 *     ),
 *      @OA\Response(response=200, description="OK")
 *)
 */
class ApiCommunityFilterRequest extends ApiRequest

{
    public function rules():array
    {
        return [
            'name'=>'string',
            'tag_name'=>'string',
            'date_from'=>'date_format:Y-m-d',
            'date_to'=>'date_format:Y-m-d'
        ];
    }

    public function messages(): array
    {
        return [
            'date_from.date_format'=>$this->localizeValidation('date.incorrect_format'),
            'date_to.date_format'=>$this->localizeValidation('date.incorrect_format')
        ];
    }
}
