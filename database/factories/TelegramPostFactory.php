<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TelegramPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'channel_id' => null,
            'post_id' => "-" . rand(700000000, 799999999),
            'text' => $this->faker->text(100),
            'datetime_record_reaction' => null,
        ];
    }

    public function setChannelId() {

    }

    public function setDatetime() {

    }
}
