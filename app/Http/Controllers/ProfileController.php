<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\ChangePassRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function passChange(ChangePassRequest $request)
    {
       $user = Auth::user();
       $user->password = bcrypt($request['password']);
       $user->save();

       return redirect()->back()->withMessage('Пароль успешно изменен');
    }
}
