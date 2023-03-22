<?php

namespace App\Http\ApiRequests;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/user/bot/action-log/filter",
 *  operationId="bot-action-list-filter",
 *  summary="Get paginated filtered list of bot action in community owned by auth user",
 *  security={{"sanctum": {} }},
 *  tags={"Bot actions list"},
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
 *         name="community_id",
 *         in="query",
 *         description="Community ID",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *             example="10"
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
 *         name="tags",
 *         in="query",
 *         description="List of tags ids",
 *         required=false,
 *         @OA\Schema(
 *            type="array",
 *              @OA\Items(type="integer"),
 *              example={1,2}
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */



class ApiTelegramActionLogFilterRequest extends ApiRequest
{

    public function rules():array
    {
        return [
            'tags'=>'array',
            'event'=>'string',
            'action_date_from'=>'date_format:Y-m-d',
            'action_date_to'=>'date_format:Y-m-d',
            'community_id'=>'integer|min:1|exists:communities,id'
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
