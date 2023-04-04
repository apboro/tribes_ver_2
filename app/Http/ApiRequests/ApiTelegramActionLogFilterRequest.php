<?php

namespace App\Http\ApiRequests;

/**
 * @OA\Get(
 *  path="/api/v3/user/bot/action-log/filter",
 *  operationId="bot-action-list-filter",
 *  summary="Get paginated filtered list of bot action in community owned by auth user",
 *  security={{"sanctum": {} }},
 *  tags={"Bot actions list"},
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
 *     @OA\Parameter(
 *         name="user_name",
 *         in="query",
 *         description="Filter by first_name or last_name or user_name",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             example="Аким"
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="event",
 *         in="query",
 *         description="Event Name",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             example="newChatUser"
 *         )
 *     ),
 *  @OA\Parameter(
 *         name="community_title[]",
 *         in="query",
 *         description="Community ID",
 *         required=false,
 *         @OA\Schema(
 *             type="array",
 *              @OA\Items(type="string"),
 *             example={"Sherlock","Holms"}
 *         )
 *     ),
 *  @OA\Parameter(
 *         name="action_date_from",
 *         in="query",
 *         description="Start date of action to search",
 *         required=false,
 *         @OA\Schema(
 *             type="date",
 *             example="2022-01-01"
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="action_date_to",
 *         in="query",
 *         description="End date of action to search",
 *         required=false,
 *         @OA\Schema(
 *             type="date",
 *             example="2023-12-01"
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="tags_names[]",
 *         in="query",
 *         description="List of tags names",
 *         required=false,
 *         @OA\Schema(
 *            type="array",
 *              @OA\Items(type="string"),
 *              example={"Crow","Sparrow"}
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK"),
 *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/api_response_token_mismatch")),
 *)
 */

class ApiTelegramActionLogFilterRequest extends ApiRequest
{
    public function rules():array
    {
        return [
            'user_name' =>'string',
            'tags'=>'array',
            'event'=>'string',
            'action_date_from'=>'date_format:Y-m-d',
            'action_date_to'=>'date_format:Y-m-d',
            'community_title'=>'array'
        ];
    }

    public function messages(): array
    {
        return [
            'action_date_from.date_format'=>$this->localizeValidation('date.incorrect_format'),
            'action_date_to.date_format'=>$this->localizeValidation('date.incorrect_format'),
            'tags.array'=>$this->localizeValidation('tag.array_incorrect_format')
        ];
    }
}
