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
        'community_id'	 => null,
        'main_image_id'	 => "0",
        'success_image_id'	 => "0",
        'description'	 => $this->faker->text(255),
        'success_description'	 => $this->faker->text(255),
        'isSendToCommunity'	 => array_rand([0,1]),
        'inline_link'	 => Str::random(8),
        'prompt_description'	 => $this->faker->text(255),
        'prompt_image_id'	 => "0",
        'isAutoPrompt'	 => array_rand([0,1]),
        'prompt_at_hours'	 => rand(1, 10),
        'prompt_at_minutes'	 => rand(1, 60),
        'title'	 => $this->faker->text(100),
        'index' => rand(1,10),

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
