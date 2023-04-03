<?php

namespace App\Http\ApiRequests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

class ApiWhiteListFilterRequest extends ApiRequest
{
    /**
     * @OA\Post(
     *  path="/api/v3/user/white-list",
     *  operationId="white-list-filter",
     *  summary="Filter white list of telegram user",
     *  security={{"sanctum": {} }},
     *  tags={"White list"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                          property="community_id",
     *                          type="integer",
     *                 ),
     *                 @OA\Property(
     *                          property="telegram_name",
     *                          type="string",
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
