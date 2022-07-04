<?php

namespace App\Repositories\Messenger;

use App\Models\User;

interface MessengerRepositoryContract
{
    public function auth(User $user, $platform_index);

    public function onCommandEvent($command, $ctx, $platformIndex);
}
