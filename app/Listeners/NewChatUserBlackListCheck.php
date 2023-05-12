<?php

namespace App\Listeners;

use App\Events\NewChatUserJoin;
use App\Models\TelegramUserList;
use App\Repositories\TelegramUserLists\TelegramUserListsRepositry;
use App\Services\TelegramMainBotService;
use Illuminate\Support\Facades\Log;


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
     * @param object $event
     * @return void
     */
    public function handle(NewChatUserJoin $event)
    {

        try {
            //Log::log('info', 'start kick user ' . $event->chat_id . ' ' . $event->telegram_user_id);
            $telegram_list = TelegramUserList::with(['communities.connection'])->whereHas('communities.connection',
                function ($query) use ($event) {
                    $query->where('chat_id', $event->chat_id);
                })
                ->where('type', TelegramUserListsRepositry::TYPE_BAN_LIST)
                ->where('telegram_id', $event->telegram_user_id)->first();//Log::log('info', 'start kick user ' . $telegram_list->toSql());
            if ($telegram_list !== null) {
                Log::log('info', 'kicked user ' . $event->chat_id . ' ' . $event->telegram_user_id);
                $this->telegramMainBotService->kickUser(
                    config('telegram_bot.bot.botName'),
                    $event->telegram_user_id,
                    $event->chat_id
                );
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
