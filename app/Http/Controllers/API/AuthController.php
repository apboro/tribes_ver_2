<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginAsRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $user = User::where('id', $request->id)->first();

        if (empty($user)) {
            throw ValidationException::withMessages([
                'email' => ["Авторизация не удалась пользователь №{$request->id} не найден"],
            ]);
        }
        $token = $user->createToken('api-token');
        Auth::guard('web')->setUser($user);
        return response()->json([
            'status' => 'ok',
            'token' => $token->plainTextToken
        ], 200);
    }
}
