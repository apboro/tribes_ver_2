<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{

    public function definition()
    {
        return [
            'title' => $this->faker->words(3, true),
            'user_id'=>User::all()->random()->id
        ];
    }
}
