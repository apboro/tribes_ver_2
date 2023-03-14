<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiRequests\ApiUserLoginRequest;
use App\Http\ApiRequests\ApiUserLogoutRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    /**
     * Perform user login.
     *
     * @param ApiUserLoginRequest $request
     *
     * @return ApiResponse
     */
    public function login(ApiUserLoginRequest $request): ApiResponse
    {
        /** @var User|null $user */
        $user = User::query()->where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return ApiResponse::unauthorized('common.incorrect_login_or_password');
        }

        return ApiResponse::common(['token' => $user->createToken('api-token')->plainTextToken]);
    }

    /**
     * Perform user logout
     *
     * @param ApiUserLogoutRequest $request
     *
     * @return ApiResponse
     */
    public function logout(ApiUserLogoutRequest $request): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $user->tokens()->delete();

        return ApiResponse::success();
    }
}
