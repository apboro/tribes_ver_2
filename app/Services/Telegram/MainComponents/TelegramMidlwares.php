<?php

namespace App\Services\Telegram\MainComponents;

use App\Models\TelegramBotUpdateLog;
use App\Services\TelegramLogService;
use Askoldex\Teletant\Context;
use Askoldex\Teletant\States\Stage;
use App\Models\TelegramUser;
use App\Services\Telegram;
use Illuminate\Support\Facades\Log;

class TelegramMidlwares
{
    /** Промежуточный метод */
    public function bootMidlwares($bot)
    {
        try {
            $bot->middlewares([
                function (Context $ctx, callable $next) {
                    $user = Telegram::registerTelegramUser($ctx->getUserID() ?? 507752964, NULL, $ctx->getUsername(), $ctx->getFirstName(), $ctx->getLastName());
                    $ctx->getContainer()->singleton(TelegramUser::class, function () use ($user) {
                        return $user;
                    });
                    $next($ctx);
                },
                function (Context $ctx, callable $next) {
                    /** @var TelegramUser $user */
                    $user = $ctx->getContainer()->get(TelegramUser::class);
                    $storage = new Storage($user);
                    $ctx->setStorage($storage);
                    $next($ctx);
                },
                function (Context $ctx, callable $next) {
                    TelegramBotUpdateLog::create([
                        'data'=>json_encode($ctx->update()->export())
                    ]);
                    $next($ctx);
                },
                $this->bootStage()->middleware()
            ]);
        } catch (\Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    /** Подключение сцены */
    protected function bootStage()
    {
        $stage = new Stage;
        $stage->addScenes(...Scenes::getAllScene());
        return $stage;
    }
}
