<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

class ApiBlackListDeleteRequest extends ApiRequest
{
    /**
     * @OA\POST(
     *  path="/api/v3/user/black-list/delete",
     *  operationId="delete-from-black-list",
     *  summary="Delete telegram user-community connection from black list",
     *  security={{"sanctum": {} }},
     *  tags={"Black list"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             encoding={
     *                  "community_ids[]": {
     *                      "explode": true,
     *                  },
     *              },
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="telegram_id",
     *                     type="integer",
     *                     example="1"
     *                 ),
     *                 @OA\Property(
     *                          property="community_ids[]",
     *                          type="array",
     *                          @OA\Items(
     *                              type="integer",
     *                          ),
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
            'telegram_id'=>'required|integer|exists:telegram_users,telegram_id|exists:telegram_user_lists,telegram_id',
            'community_ids'=>'required|array',
            'community_ids.*' => 'integer|exists:communities,id',
        ];
    }

    public function messages(): array
    {
        return [
            'telegram_id.required'=>$this->localizeValidation('telegram_user.required_telegram_id'),
            'telegram_id.integer'=>$this->localizeValidation('telegram_user.integer_telegram_id'),
            'telegram_id.exists'=>$this->localizeValidation('telegram_user.exists_telegram_id'),
            'community_ids.required'=>$this->localizeValidation('community.id_required'),
            'community_ids.array'=>$this->localizeValidation('community.array'),
        ];
    }
}
