<?php

namespace App\Http\ApiRequests;

use OpenApi\Annotations as OA;

/**
 * @OA\Post(
 *  path="/api/v3/anispam",
 *  operationId="antispam-add",
 *  summary="Add antispam",
 *  security={{"sanctum": {} }},
 *  tags={"Antispam"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="name",type="string",example="test name"),
 *                 @OA\Property(property="del_message_with_link",type="boolean",example="false"),
 *                 @OA\Property(property="ban_user_contain_link",type="boolean",example="false"),
 *                 @OA\Property(property="del_message_with_forward",type="boolean",example="false"),
 *                 @OA\Property(property="ban_user_contain_forward",type="boolean",example="false"),
 *                 @OA\Property(property="work_period",type="integer",example="10"),
 *             ),
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class ApiAntispamStoreRequest extends ApiRequest
{

    public function rules(): array
    {
        return [
            'name' => 'string|max:120',
            'del_message_with_link' => 'boolean',
            'ban_user_contain_link' => 'boolean',
            'del_message_with_forward' => 'boolean',
            'ban_user_contain_forward' => 'boolean',
            'work_period' => 'integer'
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => $this->localizeValidation('antispam.name_string'),
            'name.max' => $this->localizeValidation('antispam.name_max_length'),
            'del_message_with_link.boolean' => $this->localizeValidation('antispam.del_message_with_link_boolean'),
            'ban_user_contain_link.boolean' => $this->localizeValidation('antispam.ban_user_contain_link_boolean'),
            'del_message_with_forward.boolean' => $this->localizeValidation('antispam.del_message_with_forward_boolean'),
            'ban_user_contain_forward.boolean' => $this->localizeValidation('antispam.ban_user_contain_forward_boolean'),
            'work_period.integer' => $this->localizeValidation('antispam.work_period_integer'),
        ];
    }
}
