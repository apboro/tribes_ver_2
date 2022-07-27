<?php

namespace Database\Factories\Statistic;

use App\Models\Statistic\MProductUserView;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MProductUserViewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'user_id' =>  rand(1,100),
            'c_time_view' => rand(3600,10500),
        ];
    }
}
