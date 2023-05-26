<?php

namespace App\Http\Controllers;

use App\Http\ApiRequests\ApiGetUserBotSessionRequest;
use App\Http\ApiRequests\ApiStoreUserBotSessionRequest;
use App\Http\ApiResponses\ApiResponse;
use App\Models\TestData;
use App\Services\Telegram\TelegramMtproto\Event;
use App\Services\Telegram\TelegramMtproto\UserBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TelegramUserBotController extends Controller
{
    protected Event $userBotEvents;

    public function __construct(Event $userBotEvents)
    {
        $this->userBotEvents = $userBotEvents;
    }

    public function storeSession(ApiStoreUserBotSessionRequest $request)
    {
       DB::table('user_bot_session')->insert(['session'=>$request->session_string]);
       return ApiResponse::success('common.success');
    }
    public function getSession(ApiGetUserBotSessionRequest $request)
    {
        return ApiResponse::common(DB::table('user_bot_session')
            ->orderBy('id', 'desc')
            ->first()->session);
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
