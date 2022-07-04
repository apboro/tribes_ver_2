<?php

namespace Database\Factories\Knowledge;

use App\Models\Knowledge\Answer;
use Database\Factories\instanceTrait;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
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
            //'id' => null,
            'question_id' => null,
            'community_id' => null,
            'is_draft' => array_rand([0,1]),
            'context' => $this->faker->text(600),

        ];
    }

    public function draft(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_draft' => true,
            ];
        });
    }

    public function notDraft(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_draft' => false,
            ];
        });
    }
}
