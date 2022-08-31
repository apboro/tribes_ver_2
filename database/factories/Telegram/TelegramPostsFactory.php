<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TelegramPostsFactory extends Factory
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
            'channel_id' => '',
            'post_id' => '',
            'text' => '',
            'datetime_record_reaction' => '',
            'created_at' => '',
            'updated_at' => '',
        ];
    }
}
