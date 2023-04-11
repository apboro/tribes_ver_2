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
 *     @OA\Parameter(
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
 *         name="tags_names[]",
 *         in="query",
 *         description="Community tags names",
 *         required=false,
 *         @OA\Schema(
 *             type="array",
 *             @OA\Items(type="string"),
 *             example={"Bubbles", "Ponies", "Pies"}
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
 *         name="date_to",
 *         in="query",
 *         description="Communities with date of add to Spodial to",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             example="2023-01-01"
 *         )
 *     ),
 *      @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */
class ApiCommunityFilterRequest extends ApiRequest

{
    public function rules():array
    {
        return [
            'name'=>'string',
            'tags_names'=>'array',
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
