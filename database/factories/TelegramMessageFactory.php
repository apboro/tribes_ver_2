<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TelegramMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group_chat_id' => null,
            'post_id' => null,
            'telegram_user_id' => null,
            'message_id' => rand(1000000000,9999999999),//TODO узнать что за поле,
            'text' => $this->faker->text(100),
            'datetime_record_reaction' => null,
            'chat_type' => null,
            'parrent_message_id' => null,
        ];
    }
}
