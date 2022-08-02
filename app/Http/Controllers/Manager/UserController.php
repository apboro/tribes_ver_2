<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Manager\Filters\UsersFilter;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @OA\Post(
     *     path="/v2/users",
     *     tags={"UserController"},
     *     summary="Get list users",
     *     operationId="getListUsers",
     *     security={{"sanctum": {} }},
     *     @OA\RequestBody(
     *         required=false,
     *         description="Фильтры пользователей",
     *         @OA\JsonContent(
     *              ref="#/components/schemas/UserRequest"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfuly get list payments",
     *         @OA\JsonContent(
     *              @OA\Property(
     *                  property="payments",
     *                  type="object",
     *                  ref="#/components/schemas/UserResource",
     *              ),
     *         ),
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
     *     @OA\Response(
     *         response=422,
     *         description="The given data was invalid",
     *     ),
     * )
     */

    public function list(Request $request, UsersFilter $filter)
    {
        $users = User::filter($filter)->paginate($request->get('entries'));

        return response($users);
    }

    public function auth(Request $request)
    {
        return Auth::user();
    }
}
