<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\TelegramMessage;
use App\Models\User;
use App\Services\SMTP\Mailer;
use App\Services\TelegramMainBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminFeedbackController extends Controller
{
    protected TelegramMainBotService $telegramService;
    public function __construct(TelegramMainBotService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    public function list(Request $request)
    {
        return response(Feedback::query()->latest()->paginate($request->input('per_page')));
    }

    public function get(Feedback $feedback)
    {
        return response($feedback);
    }

    public function close(Feedback $feedback)
    {
        $feedback->status = 'Закрыт';
        $feedback->save();

        return response('Обращение закрыто', 200);
    }

    public function answer(Request $request)
    {
        $manager = Auth::user();
        $feedback = Feedback::where('id', $request->id)->first();
        $feedback->answer=$request->message;
        $feedback->manager_user_id = $manager->id;
        $feedback->status = 'Отвечен';
        $feedback->save();

        $answer = $feedback->answer;

        $user = User::find($feedback->user_id);
        if ($user_telegram = $user->telegramData()) {
            $this->telegramService->sendMessageFromBot(
                config('telegram_bot.bot.botName'),
                $user_telegram->telegram_id,
                'Ответ на Ваше обращение: '. $answer
            );
        }

        $textMessageView = view('mail.feedback_answer', compact('answer'))->render();
        if ($user->email) {
            new Mailer('Сервис Spodial', $textMessageView, 'Ответ на обращение', $user->email);
        }

        return response('Ответ отправлен', 200);
    }
}
