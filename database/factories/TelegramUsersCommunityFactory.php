<?php

namespace Database\Factories;

use App\Models\Community;
use App\Models\TelegramUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TelegramUsersCommunityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();
        return [
            'community_id' => Community::all()->random(),
            'telegram_user_id' => TelegramUser::all()->random(),
            'excluded' => '',
            'role' => 'participant',
            'accession_date' => Carbon::now()->timestamp,
            'exit_date' => $faker->boolean() ? $faker->dateTimeBetween(Carbon::now()->subMonth(), Carbon::now()) : null,
        ];
    }
}
