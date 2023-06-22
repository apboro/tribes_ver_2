<?php

namespace App\Http\Controllers\APIv3\User;


use App\Http\ApiRequests\Profile\UserAdditionalFieldsRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;

class ApiUserAdditionalFieldsController extends Controller
{
    /**
     * @param UserAdditionalFieldsRequest $request
     * @return ApiResponse
     */
    public function update(UserAdditionalFieldsRequest $request): ApiResponse
    {
        /** @var User $user */
        $user = $request->user();
        if ($user === null) {
            return ApiResponse::notFound('common.not_found');
        }

        $user->gender = $request->input('gender', $user->gender) ?? $user->gender;
        $user->birthdate = !empty($request->input('birthdate')) ? Carbon::parse($request->input('birthdate')) : $user->birthdate;
        $user->country = $request->input('country', $user->country);
        $user->is_see_tour = $request->input('is_see_tour',  $user->is_see_tour);

        $user->save();

        return ApiResponse::success();
    }
}
