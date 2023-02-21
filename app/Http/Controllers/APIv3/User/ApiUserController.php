<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiRequests\ApiChangePassRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePassRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiUserController extends Controller
{

    /**
     * TODO Swagger annotations
     *
     *
     * @return ApiResponse
     */

    public function show():ApiResponse
    {
        /** @var User|null $user */
        $user = Auth::user();
        return ApiResponse::common(['user'=>UserResource::make($user)]);
    }

    public function passChange(ApiChangePassRequest $request):ApiResponse
    {

        /** @var User|null $user */

        $user = Auth::user();
        $user->password = bcrypt($request['password']);
        $user->save();

        return ApiResponse::success('passwords.changed');
    }

}
