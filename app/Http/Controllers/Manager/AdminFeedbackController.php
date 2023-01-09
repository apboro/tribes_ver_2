<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\TelegramMessage;
use App\Services\TelegramMainBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminFeedbackController extends Controller
{
    public function list()
    {
        return response(Feedback::all());
    }

    public function close(Feedback $feedback)
    {
        $feedback->status = 'Закрыт';
        $feedback->save();

        return response('Обращение закрыто');
    }

    public function answer(Request $request)
    {
        $feedback = Feedback::where('id', $request->id)->first();
        $feedback->answer=$request->message;
        $feedback->manager_user_id = Auth::user()->id;
        $feedback->status = 'Отвечен';
        $feedback->save();

        (new TelegramMainBotService())->sendMessageFromBot(
            config('telegram_bot.bot.botName'),
            $user->telegram_id,
            'Privet!'
        );
        return response('Ответ отправлен');
    }
}
