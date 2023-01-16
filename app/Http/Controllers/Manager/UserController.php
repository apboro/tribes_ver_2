<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Manager\Filters\UsersFilter;
use App\Http\Requests\Auth\LoginAsRequest;
use App\Http\Requests\Manager\CommissionRequest;
use App\Http\Resources\Manager\UsersResource;
use App\Models\UserSettings;
use App\Services\Admin\UserService;
use App\Services\File\FileSendService;
use App\Services\TelegramMainBotService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    use AuthenticatesUsers;

    private UserService $userService;
    private FileSendService $fileSendService;
    private TelegramMainBotService $telegramMainBotService;


    public function __construct(
        UserService $userService,
        FileSendService $fileSendService,
        TelegramMainBotService $telegramMainBotService
    )
    {
        $this->userService = $userService;
        $this->fileSendService = $fileSendService;
        $this->telegramMainBotService = $telegramMainBotService;
    }

    public function list(Request $request, UsersFilter $filter)
    {
        $users = User::with('telegramMeta','accumulation')->filter($filter)->paginate(request('filter.entries'), ['*'], 'filter.page');
        return new UsersResource($users);
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

    public function commission(CommissionRequest $request)
    {
        $userSettings = UserSettings::findByUserId($request->id);

        if(empty($model = $userSettings->get('percent'))) {
            $model = new UserSettings([
                'user_id'=>$request->id,
                'name'=> 'percent'
            ]);
        }
        $model->value = $request->percent;
        if($rez = $model->save()){
            $status = 'ok';
            $message = "Установлен процент комиссии для пользователя";
        } else {
            $status = 'error';
            $message = "Не удалось сохранить значение";
        }

        return response()->json([
            'status' => $status,
            'message' => $message,
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

    public function block(Request $request)
    {
        $user = User::find($request->id);
        $user->is_blocked = 1;
        $user->save();
    }

    public function unblock(Request $request)
    {
        $user = User::find($request->id);
        $user->is_blocked = 0;
        $user->save();
    }

    public function sendNewPassword(Request $request)
    {
        $user = User::find($request->id);
        $password = Str::random(6);
        $user->password = Hash::make($password);
        $user->save();
        $this->telegramMainBotService->sendMessageFromBot(config('telegram_bot.bot.botName'), $user->telegramMeta->telegram_id, 'Ваш новый пароль: '. $password);
    }
}
