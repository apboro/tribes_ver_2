<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublicationFactory extends Factory
{
    protected $model = Publication::class;

    public function definition(): array
    {
        return [
            'is_active' => $this->faker->boolean(),
            'author_id' => Author::factory()->create()->id,
        ];
    }
}
