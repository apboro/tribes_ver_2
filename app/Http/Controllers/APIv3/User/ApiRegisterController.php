<?php

namespace App\Http\Controllers\APIv3\User;


use App\Events\ApiUserRegister;
use App\Http\ApiRequests\ApiRegisterRequest;
use App\Http\ApiResponses\ApiResponseSuccess;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class ApiRegisterController extends Controller
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
        event(new ApiUserRegister($user,$password));
        return $user;
    }

    public function register(ApiRegisterRequest $request)
    {
        $user = $this->create($request->all());
        $user->tinkoffSync();
        return (new ApiResponseSuccess())->payload(['token'=>$user->createToken('api-token')->plainTextToken]);
    }
}
