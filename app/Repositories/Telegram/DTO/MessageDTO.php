<?php

namespace App\Repositories\Telegram\DTO;

use Carbon\Carbon;

/**
 * @property mixed $forward
 * @property mixed $new_chat_member_id
 * @property mixed $new_chat_member_bot
 */
class MessageDTO
{
    public $message_id;
    public $telegram_user_id;
    public $group_chat_id;
    public $post_id;
    public $chat_type;
    public $chat_id;
    public $parrent_message_id;
    public $text;
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
    public $forward;
    public $new_chat_member_id;
    public $new_chat_member_bot;

    public $reply_message_id;
    public $reply_from_id;

}