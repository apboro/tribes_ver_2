<?php

namespace Database\Factories;

use App\Models\Community;
use App\Models\TelegramBotActionTypes;
use App\Models\TelegramUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class TelegramBotActionLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'action_type_id'=>$this->faker->randomElement(TelegramBotActionTypes::all())->id,
            'community_id'=>$this->faker->randomElement(Community::all())->id,
            'telegram_user_id'=>$this->faker->randomElement(TelegramUser::all())->id,
            'action_done'=>$this->faker->word
        ];
    }
}
