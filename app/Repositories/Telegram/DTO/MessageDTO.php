<?php

namespace App\Repositories\Telegram\DTO;

class MessageDTO
{
    public $message_id;
    public $telegram_user_id;
    public $group_chat_id;
    public $post_id;
    public $chat_type;
    public $parrent_message_id;
    public $text;
    public $telegram_date;
}