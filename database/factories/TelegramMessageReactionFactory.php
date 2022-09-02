<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TelegramMessageReactionFactory extends Factory
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
            'message_id' => null,
            'reaction_id' => null,
            'telegram_user_id' => null,
            'datetime_record' => null,
            'group_chat_id' => null,

        ];
    }

    public function setMessageId($message): TelegramMessageReactionFactory
    {
        return $this->state(function (array $attributes) use ($message) {
            return [
                'message_id' => $message->id,
            ];
        });
    }

    public function setTelegramUserId($telegramUser): TelegramMessageReactionFactory
    {
        return $this->state(function (array $attributes) use ($telegramUser) {
            return [
                'telegram_user_id' => $telegramUser->id,
            ];
        });
    }

}
