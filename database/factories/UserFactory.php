<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @method User getItemByAttrs(array $attributes)
 */
class UserFactory extends Factory
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
            //'id' => null,
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'remember_token' => Str::random(10),
            'hash' => Str::random(10),
            'code' => rand(1000,9999),
            'phone' => rand(9510000000,9519999999),
            'password' => bcrypt('test123'),
            'phone_confirmed' => true,
            'locale' => 'ru',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
