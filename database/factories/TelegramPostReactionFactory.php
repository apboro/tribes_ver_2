<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TelegramPostReactionFactory extends Factory
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
            'post_id' => null,
            'reaction_id' => null,
            'count' => rand(10,20),
            'datetime_record' => null,
            'chat_id' => 1,
//            'created_at' => '',
//            'updated_at' => '',
        ];
    }
}
