<?php

namespace App\Listeners;

use App\Events\NewChatUserJoin;
use App\Models\TelegramUserList;
use App\Repositories\TelegramUserLists\TelegramUserListsRepositry;
use App\Services\TelegramMainBotService;


class NewChatUserBlackListCheck
{
    private TelegramMainBotService $telegramMainBotService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(TelegramMainBotService $telegramMainBotService)
    {
        //
        $this->telegramMainBotService = $telegramMainBotService;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NewChatUserJoin $event)
    {
        $telegram_list = TelegramUserList::with(['communities'])->
        whereHas('communities', function ($q) use ($event) {
            $q->where('community_id', $event->chat_id)->
            where('type',TelegramUserListsRepositry::TYPE_BLACK_LIST)->
            where('telegram_id',$event->telegram_user_id);
        })->first();
        if($telegram_list !== null){
            $this->telegramMainBotService->kickUser(
                config('telegram_bot.bot.botName'),
                $event->telegram_user_id,
                $event->chat_id
            );
        }
    }
}
