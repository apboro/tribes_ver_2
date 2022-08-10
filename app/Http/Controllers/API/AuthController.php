<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
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

    public function loginAs(LoginRequest $request)
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
}
