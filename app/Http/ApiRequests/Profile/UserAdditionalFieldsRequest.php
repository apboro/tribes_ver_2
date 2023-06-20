<?php

namespace App\Http\ApiRequests\Profile;

use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Put(
 *  path="/api/v3/users/additional-fields",
 *  operationId="edit-user-additional-fields",
 *  summary="Edit user additional fields",
 *  security={{"sanctum": {} }},
 *  tags={"User"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="gender",type="string",example="m"),
 *                 @OA\Property(property="birthdate",type="date",example="01.01.2000"),
 *                 @OA\Property(property="country",type="string",example="США"),
 *             ),
 *         )
 *     ),
 *   @OA\Response(response=200, description="OK")
 *)
 */
class UserAdditionalFieldsRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'gender' => 'nullable|in:m,f',
            'birthdate' => 'nullable|date_format:d.m.Y',
            'country' => 'nullable|string'
        ];
    }
}
