<?php

namespace App\Http\Controllers\APIv3\User;


use App\Services\SMTP\Mailer;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterRequest;

use Illuminate\Support\Str;
use App\Mail\SendPassword;


class RegisterController extends Controller
{
    use RegistersUsers;

    protected function create(array $data)
    {
        $password = Str::random(6);
        $user = User::create([
            'name' => isset($data['name']) ? $data['name'] : 'No name yet',
            'email' => strtolower($data['email']),
            'password' => Hash::make($password),
            'role_index' => isset($data['role_index']) ? $data['role_index'] : 0,
            'phone_confirmed' => false,
        ]);

        $user->tinkoffSync();

        $user->hashMake();

        $v = view('mail.registration')->with(['login' => $data['email'],'password' => $password])->render();
        new Mailer('Сервис ' . env('APP_NAME'), $v, 'Регистрация', $data['email']);

        return $user;
    }

    public function register(RegisterRequest $request)
    {
        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        Auth::user()->createTempToken();

        return redirect()->route('author.profile');

//        return redirect()->route('login')->with('message', 'Пароль отправлен на указанную почту.');

    }

}
