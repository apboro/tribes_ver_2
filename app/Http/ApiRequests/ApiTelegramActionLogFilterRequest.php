<?php

namespace App\Http\ApiRequests;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/user/bot-action-log/filter",
 *  operationId="bot-action-list-filter",
 *  summary="Get paginated filtered list of bot action in community owned by auth user",
 *  security={{"sanctum": {} }},
 *  tags={"Bot actions list"},
 *   @OA\RequestBody(
 *     @OA\JsonContent(
 *       @OA\Property(property="action_date_from", type="string", example="2023-01-01"),
 *       @OA\Property(property="action_date_to", type="string", example="2023-12-01"),
 *       @OA\Property(property="tags", type="array", @OA\Items(
 *               type="number",
 *               description="The tag ID",
 *               @OA\Schema(type="number"),
 *               example="1"
 *         )
 *       ),
 *       @OA\Property(property="community_id", type="integer", example="2"),
 *     )
 *   ),
 *   @OA\Response(response=200, description="OK")
 *)
 */

class ApiTelegramActionLogFilterRequest extends ApiRequest
{

    public function rules():array
    {
        return [
            'tags'=>'array',
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
