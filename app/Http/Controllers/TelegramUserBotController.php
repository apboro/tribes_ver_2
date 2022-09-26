<?php

namespace App\Http\Controllers;

use App\Models\TestData;
use App\Services\Telegram\TelegramMtproto\Event;
use App\Services\Telegram\TelegramMtproto\UserBot;
use Illuminate\Http\Request;

class TelegramUserBotController extends Controller
{
    protected Event $userBotEvents;

    public function __construct(Event $userBotEvents)
    {
        $this->userBotEvents = $userBotEvents;
    }

    public function index(Request $request)
    {
        $data = $request->collect();
        if ($data) {
            TestData::create([
                'data' => $data
            ]);
            $this->userBotEvents->handler($data);
        } else {
            return false;
        }
    }

    public function setWebhook()
    {
        (new UserBot())->setWebhook(route('user.bot.webhook'));
        return redirect()->back();
    }
}
