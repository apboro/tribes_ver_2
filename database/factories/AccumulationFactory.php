<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AccumulationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //'user_id' => null,
            "SpAccumulationId" => Str::random(255),
            "amount" => rand(100,150000),
            "started_at" => (new Carbon())->now()->subWeek(1),
            "ended_at" => (new Carbon())->now()->addMonth(1),
            "status" => 'active',
            ];
    }

    public function user($id): AccumulationFactory
    {
        return $this->state(function (array $attributes) use ($id) {
            return [
                'user_id' => $id
            ];
        });

    }

    public function amount($amount): AccumulationFactory
    {;
        return $this->state(function (array $attributes) use ($amount) {
            return [
                'amount' => $amount
            ];
        });
    }
}
