<?php

namespace App\Http\Controllers\APIv3\User;

use App\Events\UserDeleteEvent;
use App\Http\ApiRequests\Profile\ApiShowUserRequest;
use App\Http\ApiRequests\Profile\ApiUserChangePassRequest;
use App\Http\ApiResources\UserResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\ApiResponses\ApiResponseSuccess;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class ApiUserController extends Controller
{
    /**
     *
     * @return ApiResponse
     */
    public function show(ApiShowUserRequest $request): ApiResponse
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


    /**
     * @return ApiResponseSuccess
     */
    public function delete()
    {
        /** @var User */
        $user_data = Auth::user();
        $user = User::find($user_data->id);
        $user->delete();
        Event::dispatch(new UserDeleteEvent($user));
        return ApiResponse::success('common.users.delete');
    }
}
