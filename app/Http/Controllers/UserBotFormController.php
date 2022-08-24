<?php

namespace App\Http\Controllers;

use App\Services\Telegram\TelegramApi\UserBot;
use Illuminate\Http\Request;

class UserBotFormController extends Controller
{
    public function index(Request $request)
    {
        $userBot = new UserBot();
        $dialogs = $userBot->getDialogs();

        $data = null;
        if ($request->method) {
            switch ($request->method) {
                case 'getMessages':
                    $data = $userBot->getMessages($request->chat_id, $request->type, $request->access_hash ?? null);
                    break;
                case 'getMessagesViews':
                    $data = $userBot->getMessagesViews($request->chat_id, $request->type, [$request->message_id], $request->access_hash ?? null);
                    break;
                case 'getChannelReactions':
                    $data = $userBot->getChannelReactions($request->chat_id, [$request->message_id], $request->access_hash);
                    break;
                case 'getReactions':
                    $data = $userBot->getReactions($request->chat_id, [$request->message_id]);
                    break;
                case 'getChatInfo':
                    $data = $userBot->getChatInfo($request->chat_id);
                    break;
                case 'getUsersInChannel':
                    $data = $userBot->getUsersInChannel($request->chat_id, $request->access_hash);
                    break;
            }
        }

        return view('user_bot_form')->withDialogs($dialogs)->withData($data);
    }
}
