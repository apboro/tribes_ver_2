<?php

namespace Database\Factories;

use App\Models\Community;
use App\Models\TelegramUser;
use Carbon\Carbon;
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
            'created_at' => $this->faker->dateTimeBetween(Carbon::now()->subYear(), Carbon::now())
        ];
    }
}
