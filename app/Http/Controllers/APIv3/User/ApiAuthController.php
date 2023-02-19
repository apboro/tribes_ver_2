<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\ApiRequests\ApiLoginRequest;
use App\Http\ApiResponses\ApiResponseSuccess;
use App\Http\ApiResponses\ApiResponseValidationError;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePassRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    public function login(ApiLoginRequest $request){
        $user = User::where('email', $request->input('email'))->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return (new ApiResponseValidationError())->message('incorrect_login_or_password');
        }
        return (new ApiResponseSuccess())->payload(['token'=>$user->createToken('api-token')->plainTextToken]);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return new ApiResponseSuccess();
    }


    /*
    public function old_login(LoginRequest $request)
    {
        $user = User::where('email', strtolower($request->email))->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Авторизация не удалась'],
            ]);
        }
        $token = $user->createToken('api-token');
        Session::put('current_token', $token->plainTextToken);
        return response()->json([
            'status' => 'ok',
            'token' => $token->plainTextToken
        ], 200);
    }

*/
    /*
    public function logout(LogoutRequest $request)
    {
        Auth::user()->tokens()->delete();

        $this->guard()->logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
*/

/*
    public function passChange(ChangePassRequest $request)
    {
        $user = Auth::user();
        $user->password = bcrypt($request['password']);
        $user->save();

        return redirect()->back()->withMessage('Пароль успешно изменен');
    }
*/

}
