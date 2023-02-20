<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiRequests\ApiForgotPasswordLinkRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\ApiResponses\ApiResponseError;
use App\Http\ApiResponses\ApiResponseSuccess;
use App\Http\ApiResponses\ApiResponseValidationError;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

class ApiForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * TODO Swagger annotations
     *
     * @param ApiForgotPasswordLinkRequest $request
     *
     * @return ApiResponse
     */
    public function sendPasswordResetLink(ApiForgotPasswordLinkRequest $request): ApiResponse
    {
        /** @var User|null $user */
        $user = User::query()->where('email', '=', $request->input('email'))->first();

        if ($user === null) {
            return (new ApiResponseValidationError())->message('user_dosent_exists');
        }

        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        if ($response !== Password::RESET_LINK_SENT) {
            return ApiResponse::error($response);
        }

        return ApiResponse::success($response);
    }
}
