<?php

namespace Database\Factories;

use App\Models\Community;
use App\Models\TelegramUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class TelegramUserListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'telegram_id' => TelegramUser::all()->random()->telegram_id,
            'community_id' => Community::all()->random()->id,
            'type' => $this->faker->numberBetween(1, 4),
        ];
    }
}
