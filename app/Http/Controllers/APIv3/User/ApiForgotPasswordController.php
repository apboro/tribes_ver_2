<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiRequests\ApiForgotPasswordLinkRequest;
use App\Http\ApiResponses\ApiResponseError;
use App\Http\ApiResponses\ApiResponseSuccess;
use App\Http\ApiResponses\ApiResponseValidationError;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordLinkRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ApiForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function sendPasswordResetLink(ApiForgotPasswordLinkRequest $request){
        $user = User::where('email','=',$request->input('email'))->first();
        if(empty($user)){
            return (new ApiResponseValidationError())->message('user_dosent_exists');
        }
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );
        return $response == Password::RESET_LINK_SENT ?
            (new ApiResponseSuccess()):
            (new ApiResponseError());
    }
}
