<?php

namespace Database\Factories\Statistic;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MProductSaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'payment_id' => rand(1,100),
            'uuid' => Str::uuid(),
            'user_id' => rand(1,100),
            'price' => rand(5000,50000),
        ];
    }
}
