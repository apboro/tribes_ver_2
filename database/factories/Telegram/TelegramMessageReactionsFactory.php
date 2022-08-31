<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TelegramMessageReactionsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
//            'id' => '',
            'message_id' => '',
            'reaction_id' => '',
            'telegram_user_id' => '',
            'datetime_record' => '',
            'group_chat_id' => '',
//            'created_at' => '',
//            'updated_at' => '',

        ];
    }

    public function setMessageId($message): TelegramMessageReactionsFactory
    {
        return $this->state(function (array $attributes) use ($message) {
            return [
                'message_id' => $message->id,
            ];
        });
    }

}
