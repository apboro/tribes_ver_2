<?php

namespace Database\Factories;

use App\Models\Community;
use App\Models\TelegramUser;
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
            'community_id' => Community::all()->random(),
            'telegram_user_id' => TelegramUser::all()->random(),
            'excluded' => '',
            'role' => 'participant',
            'accession_date' => Carbon::now()->timestamp,
            'exit_date' => null,
        ];
    }
}
