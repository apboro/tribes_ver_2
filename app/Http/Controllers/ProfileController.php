<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ChangePassRequest;
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
