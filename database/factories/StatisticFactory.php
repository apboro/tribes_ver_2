<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StatisticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //'id' => null,
            'community_id' => null,
            'hosts' => rand(100,5000),
            'views' => rand(100,5000),
        ];
    }
}
