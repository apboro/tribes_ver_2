<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Manager\Filters\UsersFilter;
use App\Http\Requests\Auth\LoginAsRequest;
use App\Http\Requests\Manager\CommissionRequest;
use App\Http\Resources\Manager\UserResource;
use App\Http\Resources\Manager\UsersResource;
use App\Models\UserSettings;
use App\Services\Admin\UserService;
use App\Services\File\FileSendService;
use App\Services\SMTP\Mailer;
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
                'title' => 'E-mail',
                'attribute' => 'email',
            ],
            [
                'title' => 'Телефон',
                'attribute' => 'phone',
            ],
            [
                'title' => 'Дата регистрации',
                'attribute' => 'created_at',
            ],
            [
                'title' => 'Количество сообществ',
                'attribute' => 'community_owner_num',
            ],
            [
                'title' => 'Последняя активность',
                'attribute' => 'updated_at',
            ],
            [
                'title' => 'Сумма поступлений',
                'attribute' => 'payins',
            ],
            [
                'title' => 'Комиссия',
                'attribute' => 'commission',
            ],
        ];
        return $this->fileSendService->sendFile(
            User::query(), 
            $names,
            UserResource::class,
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
        if ($user->telegramMeta) {
            $this->telegramMainBotService->sendMessageFromBot(config('telegram_bot.bot.botName'), $user->telegramMeta->telegram_id, 'Ваш новый пароль: ' . $password);
        }
        $v = view('mail.remind_password')->with(['password' => $password])->render();
        new Mailer('Сервис ' . env('APP_NAME'), $v, 'Восстановление доступа', $user->email);

    }
}
