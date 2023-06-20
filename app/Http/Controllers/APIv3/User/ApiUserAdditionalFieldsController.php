<?php

namespace App\Http\Controllers\APIv3\User;


use App\Http\ApiRequests\Profile\UserAdditionalFieldsRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ApiUserAdditionalFieldsController extends Controller
{
    /**
     * @param UserAdditionalFieldsRequest $request
     * @return ApiResponse
     */
    public function update(UserAdditionalFieldsRequest $request): ApiResponse
    {
        $user_data = Auth::user();
        $user = User::find($user_data->id);
        if ($user === null) {
            return ApiResponse::notFound('common.not_found');
        }
        $user->fill([
            'gender' => $request->input('gender') ?? null,
            'birthdate' => !empty($request->input('birthdate')) ? Carbon::parse($request->input('birthdate')) : null,
            'country' => $request->input('country') ?? null,
        ]);
        $user->save();
        return ApiResponse::success();
    }
}
