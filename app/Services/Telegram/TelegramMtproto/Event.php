<?php

namespace App\Services\Telegram\TelegramMtproto;

use App\Jobs\SetNewTelegramUsers;
use App\Models\TestData;
use App\Services\TelegramLogService;

class Event
{

    public function handler($update)
    {
        $update = json_decode($update['data'][0], false);
        $this->newParticipants($update);
    }

    protected function newParticipants($update)
    {

        $participants = $update->participants ?? null;

        if ($participants && $update->_ === 'updateChatParticipants') {
            TestData::create([
                'data' => json_encode($participants->participants)
            ]);
            foreach ($participants->participants as $participant) {
                if ($participant->user_id === config('telegram_user_bot.user_bot.id')) {
                    dispatch(new SetNewTelegramUsers($participants->chat_id))->delay(5);
                }
            }
        }
    }
}
