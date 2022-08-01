<?php

namespace Database\Factories\Statistic;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => Str::uuid(),
            'type' => 'course',
            'c_uniq_buyers' => rand(1,100),
            'c_time_view' => rand(3600,10500),
        ];
    }
}
