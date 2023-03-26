<?php

namespace App\Http\ApiRequests;



use OpenApi\Annotations as OA;

class ApiBlackListFilterRequest extends ApiRequest
{

    /**
     * @OA\Post(
     *  path="/api/v3/user/black-list/filter",
     *  operationId="black-list-filter",
     *  summary="Filter black list of telegram user",
     *  security={{"sanctum": {} }},
     *  tags={"Black list"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                          property="community_id",
     *                          type="integer",
     *                 ),
     *                 @OA\Property(
     *                          property="telegram_name",
     *                          type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="is_spammer",
     *                     type="integer",
     *                     example="1"
     *                 ),
     *             ),
     *         )
     *     ),
     *   @OA\Response(response=200, description="OK")
     *)
     */

    public function rules():array
    {
        return [
            'telegram_id'=>'integer|exists:telegram_users,telegram_id|exists:telegram_user_black_lists,telegram_id',
            'community_id'=>'integer|exists:communities,id',
            'telegram_name'=>'string',
            'is_spammer'=>'integer'
        ];
    }

    public function messages(): array
    {
        return [
            'telegram_id.integer'=>$this->localizeValidation('telegram_user.integer_telegram_id'),
            'telegram_id.exists'=>$this->localizeValidation('telegram_user.exists_telegram_id'),
            'community_id.integer'=>$this->localizeValidation('community.integer'),
            'community_id.exists'=>$this->localizeValidation('community.id_exists'),
            'is_spammer.integer'=>$this->localizeValidation('telegram_user.is_spammer'),
        ];
    }
}
