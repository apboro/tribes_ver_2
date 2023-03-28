<?php

namespace App\Repositories\Telegram\DTO;

use Carbon\Carbon;

class MessageDTO
{
    public int $message_id;
    public int $telegram_user_id;
    public int $group_chat_id;
    public int $post_id;
    public string $chat_type;
    public int $chat_id;
    public $parrent_message_id;
    public string $text;
    public $telegram_date;
    /**
     * @var mixed
     */
    public $telegram_user_first_name;
    /**
     * @var mixed
     */
    public $telegram_user_username;
    /**
     * @var mixed
     */
    public $telegram_user_last_name;
    /**
     * @var mixed
     */
    public $message_entities;
}