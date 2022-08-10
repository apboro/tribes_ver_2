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
                'email' => ["Авторизация не удалась пользователь №{$request->id} не найден"],
            ]);
        }
        $token = $user->createToken('api-token');
        Auth::guard('web')->loginUsingId($user->id, TRUE);
        Session::flush();
        session()->regenerateToken();
        Session::put('admin_id',$admin->id);
        $csrf = Session::token();

        return response()->json([
            'status' => 'ok',
            'token' => $token->plainTextToken,
            'csrf' => $csrf,
        ], 200);
    }
}
