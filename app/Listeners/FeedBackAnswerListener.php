<?php

namespace App\Listeners;

use App\Events\FeedBackAnswer;
use App\Services\SMTP\Mailer;
use App\Services\TelegramMainBotService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FeedBackAnswerListener
{
    private TelegramMainBotService $telegramService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(TelegramMainBotService $telegramService)
    {
        //
        $this->telegramService = $telegramService;
    }

    /**
     * Handle the event.
     *
     * @param  FeedBackAnswer  $event
     * @return void
     */
    public function handle(FeedBackAnswer $event)
    {
        if ($user_telegram = $event->user->telegramData()) {
            $this->telegramService->sendMessageFromBot(
                config('telegram_bot.bot.botName'),
                $user_telegram[0]->telegram_id,
                'Ответ на Ваше обращение: '. $event->answer
            );
        }

        $textMessageView = view('mail.feedback_answer', ['answer'=>$event->answer])->render();
        if ($event->user->email) {
            new Mailer('Сервис Spodial', $textMessageView, 'Ответ на обращение', $event->user->email);
        }
    }
}
