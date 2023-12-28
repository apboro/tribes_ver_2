<?php

namespace App\Listeners;

use App\Events\BuyProductEvent;
use App\Services\SMTP\Mailer;
use App\Services\TelegramMainBotService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;

class BuyProductListener
{
    private TelegramMainBotService $telegramService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(TelegramMainBotService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(BuyProductEvent $event)
    {
        //$event->user->telegramMeta->first()->telegram_id
        $user_telegram = $event->user->telegramData();
        if (count($user_telegram) > 0 && isset($user_telegram[0]->telegram_id)) {
            $this->telegramService->sendMessageFromBot(
                config('telegram_bot.bot.botName'),
                $user_telegram[0]->telegram_id,
                $event->message
            );
        }

        $text = $event->message;

        /** @see spodial_backend/resources/views/mail/email_default_message.blade.php:85 */
        $textMessageView = view('mail.email_default_message', ['text'=>$text])->render();
        if ($event->user->email) {
         //   new Mailer('Сервис Spodial', $textMessageView, 'Ответ на обращение', $event->user->email);
        }
    }
}
