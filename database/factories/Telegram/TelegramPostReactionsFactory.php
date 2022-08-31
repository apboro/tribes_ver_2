<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TelegramPostReactionsFactory extends Factory
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
            'post_id' => '',
            'reaction_id' => '',
            'count' => '',
            'datetime_record' => '',
//            'created_at' => '',
//            'updated_at' => '',
        ];
    }
}
