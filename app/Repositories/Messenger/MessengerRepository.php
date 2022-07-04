<?php

namespace App\Repositories\Messenger;

use App\Models\User;
use App\Services\Telegram;

class MessengerRepository implements MessengerRepositoryContract
{
    private $activeService;

    private $platform = [
        'Telegram' => Telegram::class,
    ];

    public function auth(User $user, $platformIndex)
    {
        $messengerService = $this->platform[$platformIndex];

        /* @var $messengerService Telegram */
        $res = $messengerService::authorize($user);

        return $res;
    }

    public function onCommandEvent($command, $ctx, $platformIndex)
    {
        $messengerService = $this->platform[$platformIndex];
        dd($messengerService);
        $messengerService->onCommand($command, $ctx);
    }

    public function onMessageEvent($command, $ctx, $platformIndex)
    {
        $messengerService = $this->platform[$platformIndex];

        $messengerService->onCommand($command, $ctx);
    }



    public function sendMessage($user_id, $message, $platformIndex)
    {
        
    }


}
