<?php

namespace App\Http\ApiRequests;

use App\Models\User;

/**
 * @OA\Post(
 *  path="/api/v3/user/telegram/assign",
 *  operationId="assign_telegram_account",
 *  summary="Assign Telegram Account",
 *  security={{"sanctum": {} }},
 *  tags={"User"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="first_name",type="string"),
 *                 @OA\Property(property="username",type="string"),
 *                 @OA\Property(property="auth_date",type="integer"),
 *                 example={"id": 5826257074, "name": "Jessica Smith", "username": "apboro", "auth_date": 1676970691}
 *             )
 *      )
 * ),
 *      @OA\Response(response=200, description="Telegram account assigned successfully", @OA\JsonContent(
 *          @OA\Property(property="message", type="string", nullable=true),
 *          @OA\Property(property="payload", type="array", @OA\Items(), example={}))
 *      ),
 *      @OA\Response(response=422, description="Validation Error", @OA\JsonContent(ref="#/components/schemas/api_response_validation_error")),
 *      @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/api_response_unauthorized")),
 *
 *)
 */
class ApiAssignTelegramRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'first_name' => 'required|string',
            'username' => 'required|string',
            'auth_date' => 'required|integer',
            'hash' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
        ];
    }

}