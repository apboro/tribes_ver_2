<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiRequests\ApiLoginRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

class ApiAuthController extends Controller
{
    /**
     * TODO Swagger annotation
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
     * TODO Swagger annotation
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
