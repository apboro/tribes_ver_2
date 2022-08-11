<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginAsRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Авторизация не удалась'],
            ]);
        }
        return response()->json([
            'status' => 'ok',
            'token' => $user->createToken('api-token')->plainTextToken
        ], 200);
    }

    public function loginAs(LoginAsRequest $request)
    {
        //dd(123);
        $user = User::where('id', $request->id)->first();
        $admin = Auth::user();
        if (empty($user)) {
            throw ValidationException::withMessages([
                'id' => ["Авторизация не удалась пользователь №{$request->id} не найден"],
            ]);
        }
        $token = $user->createToken('api-token');
        Auth::guard('web')->loginUsingId($user->id, TRUE);
        Session::flush();
        session()->regenerateToken();
        Session::put('admin_id',$admin->id);
        Session::put('current_token',$token->plainTextToken);
        $csrf = Session::token();

        return response()->json([
            'status' => 'ok',
            'token' => $token->plainTextToken,
            'csrf' => $csrf,
            'redirect' => route('community.list'),
        ], 200);
    }

    public function loginAsAdmin(Request $request)
    {

        if(!($adminId = session()->get('admin_id')) || empty($adminId)) {
            throw ValidationException::withMessages([
                'admin_id' => ["Авторизация не удалась сессия не имеет ключа admin_id"],
            ]);
        }
        $admin = User::find($adminId);
        $currentUserToken = session()->get('current_token');
        $user = Auth::user();

        $pTokenModel = PersonalAccessToken::findToken($currentUserToken);
        if(!empty($pTokenModel)) {
            $pTokenModel->delete();
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();

        Auth::guard('web')->loginUsingId($admin->id, TRUE);
        Session::flush();
        session()->regenerateToken();
        //$token = $admin->tokens()
        return response()->json([
            'status' => 'ok',
            'csrf' => session()->token(),
            'redirect' => route('manager.users.list'),
        ], 200);
    }
}
