<?php

namespace Database\Factories\Knowledge;

use App\Models\Knowledge\Question;
use Database\Factories\instanceTrait;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @method Question getItemByAttrs(array $attributes)
 */
class QuestionFactory extends Factory
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
            'author_id' => null,
            'uri_hash' => Str::random(32),
            'c_enquiry' => rand(0,10),
            'context' => $this->faker->text(600),

        ];
    }

    public function enquiry($count): self
    {
        return $this->state(function (array $attributes) use ($count) {
            return [
                'с_enquiry' => $count,
            ];
        });
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

    public function public(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_public' => true,
            ];
        });
    }

    public function notPublic(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_public' => false,
            ];
        });
    }
}
