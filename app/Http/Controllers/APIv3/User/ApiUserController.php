<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiRequests\ApiUserChangePassRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\ApiResources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiUserController extends Controller
{
    /**
     * TODO Swagger annotations
     *
     * @param Request $request
     *
     * @return ApiResponse
     */
    public function show(Request $request): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();

        return ApiResponse::common(UserResource::make($user)->toArray($request));
    }

    /**
     * Perform logged-in user password change
     *
     * @param ApiUserChangePassRequest $request
     *
     * @return ApiResponse
     */
    public function passChange(ApiUserChangePassRequest $request): ApiResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $user->password = bcrypt($request['password']);
        $user->save();

        return ApiResponse::success('common.passwords.changed');
    }
}
