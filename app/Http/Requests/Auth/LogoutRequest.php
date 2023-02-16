<?php

namespace App\Http\Requests\Auth;


use App\Http\ApiRequests\ApiRequest;

/**
 * @OA\Post(
 *     path="api/v3/user/logout",
 *     tags={"User"},
 *     summary="Logout",
 *     operationId="Logout",
 *     security={{"sanctum": {} }},
 *     @OA\RequestBody(
 *         required=false,
 *         description="Тело запроса для входа другим пользователем",
 *         @OA\JsonContent(
 *              @OA\Property(
 *                  property="id",
 *                  type="integer",
 *              ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Redirect to main page"
 *     ),
 *     @OA\Response(
 *         response=302,
 *         description="Redirect to main page, if user is not admin"
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthenticated",
 *     ),
 *     @OA\Response(
 *         response=419,
 *         description="Page expired",
 *     ),
 * )
 *
 */
class LogoutRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

}
