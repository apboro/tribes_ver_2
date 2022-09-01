<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class TelegramUsersCommunityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'community_id' => null,
            'telegram_user_id' => null,
            'excluded' => '',
            'role' => '',
            'accession_date' => Carbon::now()->timestamp,
            'exit_date' => null,
        ];
    }
}
