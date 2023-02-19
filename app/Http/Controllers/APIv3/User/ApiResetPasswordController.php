<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiRequests\ApiResetPasswordLinkRequest;
use App\Http\ApiResponses\ApiResponseSuccess;
use App\Http\ApiResponses\ApiResponseValidationError;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordLinkRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class ApiResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    public function resetUserPassword(ApiResetPasswordLinkRequest $request)
    {
        $user = User::where('email','=',$request->input('email'))->first();
        if(empty($user)){
            return (new ApiResponseValidationError())->message('reset_password_request_not_found');
        }

        if (! $this->broker()->tokenExists($user, $request->input('token'))) {
            return (new ApiResponseValidationError())->message('reset_password_request_not_found');
        }

        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        }
        );
        if($response != Password::PASSWORD_RESET){
            return (new ApiResponseValidationError())->message($response);
        }

        return (new ApiResponseSuccess())->payload([
            'token'=>$user->createToken('api-token')->plainTextToken
        ]);
    }
}
