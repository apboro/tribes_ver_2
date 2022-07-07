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
