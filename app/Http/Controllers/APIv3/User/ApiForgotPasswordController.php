<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiRequests\Authentication\ApiUserForgotPasswordLinkRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

class ApiForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Send password reset link to user
     *
     * @param ApiUserForgotPasswordLinkRequest $request
     *
     * @return ApiResponse
     */
    public function sendPasswordResetLink(ApiUserForgotPasswordLinkRequest $request): ApiResponse
    {
        /** @var User|null $user */
        $user = User::query()->where('email', '=', $request->input('email'))->first();

        if ($user === null) {
            return ApiResponse::validationError()->addError('email', 'common.user_dosent_exists');
        }

        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        if ($response !== Password::RESET_LINK_SENT) {
            return ApiResponse::validationError()->addError('email', 'common.' . $response);
        }

        return ApiResponse::success('common.' . $response);
    }
}
