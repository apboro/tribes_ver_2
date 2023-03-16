<?php

namespace Database\Factories;

use App\Models\Community;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @method Community getItemByAttrs(array $attributes)
 */
class CommunityFactory extends Factory
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
            'connection_id' => null, //$connection->id,
            'owner' => User::all()->random(), //$userId,
            'title' => $this->faker->text(90),
            'image' => NULL,
            'description' => $this->faker->text(280),
            'balance' => rand(0,1000),
            'hash' => Str::random('32'),
        ];
    }

    public function withImage($img): CommunityFactory
    {
        return $this->state(function (array $attributes) use ($img) {
            return [
                'image' => $img,
            ];
        });
    }

    public function balance($amount): CommunityFactory
    {
        return $this->state(function (array $attributes) use ($amount) {
            return [
                'balance' => $amount,
            ];
        });
    }
}
