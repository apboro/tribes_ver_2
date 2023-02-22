<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiRequests\ApiLoginRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Schema(
 *     schema="login_success_response",
 *  @OA\Property(
 *     property="data",
 *     type="array",
 *     @OA\Items(),
 *     example={"token"="260|nAYVOcXotwMJLdTNKEiCmu8IbE5AIx2VJREAFAHM"},
 *     ),
 *     @OA\Property(
 *     property="message",
 *     type="string",
 *      ),
 * @OA\Property(
 *     property="payload",
 *     type="array",
 *     @OA\Items(),
 *     example={},
 *     ),
 * )
 */
class ApiAuthController extends Controller
{
    /**
     *
     * @param ApiLoginRequest $request
     *
     * @return ApiResponse
     */
    public function login(ApiLoginRequest $request): ApiResponse
    {
        /** @var User|null $user */
        $user = User::query()->where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return ApiResponse::validationError()->addError('email', 'common.incorrect_login_or_password');
        }

        return ApiResponse::common(['token' => $user->createToken('api-token')->plainTextToken]);
    }

    /**
     *
     * @OA\Post(
     *        path="/api/v3/user/logout",
     *        operationId="logout",
     *        summary="User logout",
     *        security={{"sanctum": {} }},
     *        tags={"User"},
     *     @OA\Response(response=200, description="Logout OK", @OA\JsonContent(ref="#/components/schemas/standart_response")),
     *     @OA\Response(response=419, description="Token mismatch", @OA\JsonContent(ref="#/components/schemas/standart_response")),
     *     @OA\Response(response=500, description="Server Error", @OA\JsonContent(ref="#/components/schemas/standart_response")),
     *
     *     ),
     *
     *
     * @return ApiResponse
     */
    public function logout(): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $user->tokens()->delete();

        return ApiResponse::success();
    }
}
