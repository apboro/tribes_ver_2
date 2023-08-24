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
 *         description="Filter by first_name or last_name or user_name",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="events[]",
 *         in="query",
 *         description="Events Array",
 *         required=false,
 *         @OA\Schema(
 *            type="array",
 *              @OA\Items(type="string"),
 *         )
 *     ),
 *  @OA\Parameter(
 *         name="community_title",
 *         in="query",
 *         description="Community Title",
 *         required=false,
 *         @OA\Schema(
 *              type="string",
 *         )
 *     ),
 *  @OA\Parameter(
 *         name="date_from",
 *         in="query",
 *         description="Start date of action to search",
 *         required=false,
 *         @OA\Schema(
 *             type="date",
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="date_to",
 *         in="query",
 *         description="End date of action to search",
 *         required=false,
 *         @OA\Schema(
 *             type="date",
 *         )
 *     ),
 *    @OA\Parameter(
 *         name="tag_names[]",
 *         in="query",
 *         description="List of tags names",
 *         required=false,
 *         @OA\Schema(
 *            type="array",
 *              @OA\Items(type="string"),
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
            'tag_names'=>'array',
            'events'=>'array',
            'date_from'=>'date_format:Y-m-d',
            'date_to'=>'date_format:Y-m-d',
            'community_title'=>'string'
        ];
    }

    public function messages(): array
    {
        return [
            'date_from.date_format'=>$this->localizeValidation('date.incorrect_format'),
            'date_to.date_format'=>$this->localizeValidation('date.incorrect_format'),
            'tags.array'=>$this->localizeValidation('tag.array_incorrect_format')
        ];
    }
}
