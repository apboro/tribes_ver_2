<?php
namespace App\Domain\Entity\Telegram;

use App\Http\ApiResponses\ApiResponse;
use App\Models\TelegramUser;
use App\Models\User;
use App\Services\Abs\Messenger;
use App\Services\Telegram;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

final class TelegramConnectionEntity
{
    // TODO Доделать  регистрацию сообщества без акаунта на сподиал!
    public static function init(int $tgUserId)
    {
//        $tgUser = TelegramUser::where('telegram_id', '=', $tgUserId)->first();
//
//        if($tgUser) {
//            $user = $tgUser->user;
//            Log::info('find User:' . $user->id);
//        }else{
//            $password = $tgUserId . '@' . $tgUserId . '.loc';
//            $user = User::easyRegister($password);
//            Log::info('not User: register new user id: ' . $user->id );
//            Log::info('not User: register new user id: ' );
//        }

        $service = app()->make(Telegram::class);
        /** @var Telegram $service */

//        $result = $service->invokeCommunityConnect($user, $this->type, $tgUser);
//        if ($result['original']['status'] === 'error') {
//            Log::error('telegram_account_not_connected');
//        }else{
//            Log::error('connection status ' . $result['original']['status']);
//        }
    }

    public static function initCompleted(int $telegramUserId)
    {
        /** @var User $user */
        $user = User::findByTelegramUserId($telegramUserId);

        if (!$user) {
            log::error(' __________  not user _________');
            exit;
        } else {
//            Auth::login($user);
            log::info('user id:' . $user->id);
        }


        log::info('_______________initCompleted ___  run _______________');
        /** @var Telegram $service */
        $service = app()->make(Telegram::class);

        Log::info('tg user id: ' . $telegramUserId);

        $result = $service->checkCommunityConnect($telegramUserId, $user);


        log::info('init complite: ' . json_encode($result, JSON_UNESCAPED_UNICODE));
    }

    private static function provideUser(int $tgUserId)
    {
        $tgUser = TelegramUser::where('telegram_id', '=', $tgUserId)->first();

        if($tgUser) {
            $user = $tgUser->user;
            Log::info('find User:' . $user->id);
        }else{
            $password = $tgUserId . '@' . $tgUserId . '.loc';
            $user = User::easyRegister($password);
            Log::info('not User: register new user id: ' . $user->id );
            Log::info('not User: register new user id: ' );
        }

        return $user;
    }
}