<?php

namespace Database\Factories;

use App\Models\Donate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @method Donate getItemByAttrs(array $attributes)
 */
class DonateFactory extends Factory
{
    use instanceTrait;
    protected static $items = [];

    public function definition()
    {
        return [
        //'id' => null,
        'description'	 => $this->faker->text(255),
        'inline_link'	 => Str::random(8),
        'title'	 => $this->faker->text(100),
        ];
    }

    public function autoPrompt($data): self
    {
        return $this->state(function (array $attributes) use ($data) {
            return [
                'isAutoPrompt' => true,
                'prompt_description' => $data['prompt_description'] ?? $this->faker->text(255),
                'prompt_at_hours' => $data['prompt_at_hours'] ?? rand(1, 10),
                'prompt_at_minutes' => $data['prompt_at_minutes'] ?? rand(1, 60),
                'prompt_image_id' => $data['prompt_image_id'] ?? "0",
            ];
        });
    }
}
