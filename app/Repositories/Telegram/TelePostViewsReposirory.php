<?php

namespace App\Repositories\Telegram;

use App\Models\TelegramPost;
use App\Models\TelegramPostViews;

class TelePostViewsReposirory implements TelePostViewsReposirotyContract
{

    public function saveViews($connect, $postsId, $views)
    {
        if (isset($views[0]->views->views)) {
            $newViews = array_combine($postsId, $views[0]->views->views);
            foreach ($newViews as $key => $view) {
                $viewsModel = new TelegramPostViews();
                $viewsModel->chat_id = $connect->chat_id;
                $viewsModel->post_id = $key;
                $viewsModel->views_count = $view->views ?? null;
                $viewsModel->datetime_record = time();
                $viewsModel->save();
            }
        }
    }
}
