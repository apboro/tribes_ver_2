<?php

namespace Database\Factories\Knowledge;

use App\Models\Knowledge\Question;
use Database\Factories\instanceTrait;
use Illuminate\Database\Eloquent\Factories\Factory;

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
}
