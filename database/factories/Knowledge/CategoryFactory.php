<?php

namespace Database\Factories\Knowledge;

use App\Models\Community;
use Database\Factories\instanceTrait;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
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
            'variant' => $this->faker->word(),
            'community_id' => Community::query()->first()->id,
            'title' => $this->faker->word,
        ];
    }
}
