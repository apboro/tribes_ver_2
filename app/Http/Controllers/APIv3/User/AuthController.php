<?php

namespace App\Http\Controllers\APIv3\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Profile\ChangePassRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     *
     * @OA\Post(
     *     path="api/v3/user/login",
     *     tags={"User"},
     *     summary="Login",
     *     operationId="Login",
     *     security={{"sanctum": {} }},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Тело запроса для входа",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  property="password",
     *                  type="string",
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Redirect to main page"
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect to main page, if user is not admin"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Page expired",
     *     ),
     * )
     *
     */

    public function login(LoginRequest $request)
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

    /**
     * @OA\Post(
     *     path="api/v3/user/logout",
     *     tags={"User"},
     *     summary="Logout",
     *     operationId="Logout",
     *     security={{"sanctum": {} }},
     *     @OA\RequestBody(
     *         required=false,
     *         description="Тело запроса для входа другим пользователем",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="id",
     *                  type="integer",
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Redirect to main page"
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect to main page, if user is not admin"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Page expired",
     *     ),
     * )
     *
     */
    public function logout(Request $request)
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
    /**
     * @OA\Post(
     *     path="api/v3/user/password/change",
     *     tags={"User"},
     *     summary="User change password",
     *     operationId="change_password",
     *     security={{"sanctum": {} }},
     *     @OA\RequestBody(
     *         required=false,
     *         description="Тело запроса для смены пароля",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="password",
     *                  type="string",
     *              ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Redirect to main page"
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect to main page, if user is not admin"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=419,
     *         description="Page expired",
     *     ),
     * )
     *
     */
    public function passChange(ChangePassRequest $request)
    {
        $user = Auth::user();
        $user->password = bcrypt($request['password']);
        $user->save();

        return redirect()->back()->withMessage('Пароль успешно изменен');
    }


}
