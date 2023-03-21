<?php

namespace Database\Factories;

use App\Models\Community;
use App\Models\TelegramBotActionTypes;
use App\Models\TelegramConnection;
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
            'action'=>'test action',
            'event'=>'test event',
            'chat_id'=>$this->faker->randomElement(TelegramConnection::all())->chat_id,
            'telegram_id'=>$this->faker->randomElement(TelegramUser::all())->telegram_id,
        ];
    }
}
