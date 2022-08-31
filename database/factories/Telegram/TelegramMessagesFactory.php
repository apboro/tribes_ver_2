<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TelegramMessagesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => '',
            'group_chat_id' => '',
            'post_id' => '',
            'telegram_user_id' => '',
            'message_id' => '',
            'text' => '',
            'datetime_record_reaction' => '',
            'created_at' => '',
            'updated_at' => '',
            'chat_type' => '',
            'parrent_message_id' => '',
        ];
    }
}
