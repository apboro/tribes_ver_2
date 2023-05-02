<?php

namespace Database\Factories\Knowledge;

use App\Models\User;
use Database\Factories\instanceTrait;
use Illuminate\Database\Eloquent\Factories\Factory;

class KnowledgeFactory extends Factory
{
    use instanceTrait;
    protected static $items = [];
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'owner_id' => User::all()->random()->id,
            'name' => $this->faker->word,
            'uri_hash' => $this->faker->uuid,
        ];
    }
}
