<?php

namespace Database\Factories;

use App\Models\TelegramUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TelegramUserFactory extends Factory
{
    use instanceTrait;
    protected static $items = [];
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //'user_id' => null,
            'telegram_id' => rand(1000000000,1269912109),
            'auth_date' => rand(1643000000,1643999999),
            'hash' => Str::random(32),
            'scene' => null,
            'first_name' => $this->faker->name(),
            'last_name' => $this->faker->lastName(),
            'photo_url' => 'https://t.me/i/userpic/320/4Ibo9h0jE4a39r-pfAxX2DWy6ZlNY_6FQSXPyWev1Zs.jpg'
        ];
    }

}
