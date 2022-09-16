<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Manager\Filters\UsersFilter;
use App\Http\Requests\Auth\LoginAsRequest;
use App\Services\Admin\UserService;
use App\Services\File\FileSendService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    use AuthenticatesUsers;

    private UserService $userService;
    private FileSendService $fileSendService;

    public function __construct(
        UserService $userService,
        FileSendService $fileSendService
    )
    {
        $this->userService = $userService;
        $this->fileSendService = $fileSendService;
    }

    public function list(Request $request, UsersFilter $filter)
    {
        $users = User::filter($filter)->paginate($request->get('entries'));

        return response($users);
    }

    public function get(Request $request)
    {
        $user = User::findOrFail($request['id']);

        return response($user);
    }

    public function auth(Request $request)
    {
        return Auth::user();
    }

    public function appointAdmin(LoginAsRequest $request)
    {
        $user = User::where('id', $request->id)->first();

        if (empty($user)) {
            throw ValidationException::withMessages([
                'id' => ["Пользователь №{$request->id} не найден"],
            ]);
        }

        $rezult = $this->userService->toggleAdminPermissions($user);

        return response()->json([
            'status' => 'ok',
            'message' => $rezult
                ? "Пользователь №{$user->id} получил права администратора"
                : "Пользователь №{$user->id} лишен прав администратора",
        ]);
    }

    public function export(Request $request, UsersFilter $filter)
    {
        $names = [
            [
                'title' => 'id',
                'attribute' => 'id',
            ],
            [
                'title' => 'Имя',
                'attribute' => 'name',
            ],
            [
                'title' => 'Телефон',
                'attribute' => 'phone',
            ],
            [
                'title' => 'E-mail',
                'attribute' => 'email',
            ],
            [
                'title' => 'Дата регистрации',
                'attribute' => 'created_at',
            ],
        ];
        return $this->fileSendService->sendFile(
            User::filter($filter),
            $names,
            null,
            $request->get('type','csv'),
            'users'
        );
    }
}
