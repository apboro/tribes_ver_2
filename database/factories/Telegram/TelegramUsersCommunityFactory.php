<?php

namespace Database\Factories;

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
        return [
            'community_id' => '',
            'telegram_user_id' => '',
            'excluded' => '',
            'role' => '',
            'accession_date' => '',
            'exit_date' => '',
        ];
    }
}
