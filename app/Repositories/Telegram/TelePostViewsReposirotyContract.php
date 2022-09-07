<?php

namespace App\Repositories\Telegram;

interface TelePostViewsReposirotyContract
{
    public function saveViews($connect, $postsId, $views);
}