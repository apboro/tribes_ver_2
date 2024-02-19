<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Administrator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user = Auth::guard('sanctum')->user() ?? null;
        if(empty($user)) {
            return abort(404);
//            return response()->json(['message' => 'пользователь как администратор не авторизован'], 403);
        }

        if ($user->isAdmin()) {

            return  $next($request);
        }

        return response()->json(['message' => 'вы - не администратор'], 401);
    }
}
