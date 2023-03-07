<?php

namespace App\Listeners;

use App\Events\RemindPassword;
use App\Services\SMTP\Mailer;
use App\Services\TelegramMainBotService;

class RemindPasswordListener
{
    private TelegramMainBotService $telegramMainBotService;

    /**
     * @param TelegramMainBotService $telegramMainBotService
     */

    public function __construct(TelegramMainBotService $telegramMainBotService)
    {

        $this->telegramMainBotService = $telegramMainBotService;
    }


    /**
     *
     * @param RemindPassword $event
     * @return void
     *
     */
    public function handle(RemindPassword $event)
    {
        if ($event->user->telegramMeta) {
            $this->telegramMainBotService->sendMessageFromBot(
                config('telegram_bot.bot.botName'),
                $event->user->telegramMeta->telegram_id,
                'Ваш новый пароль: ' . $event->password
            );
        }
        $v = view('mail.remind_password')->with(['password' => $event->password])->render();
        new Mailer('Сервис ' . env('APP_NAME'), $v, 'Восстановление доступа', $event->user->email);
    }
}
