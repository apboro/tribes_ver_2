<?php

namespace Database\Factories;

use App\Models\LMSFeedback;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LMSFeedbackFactory extends Factory
{
    protected $model = LMSFeedback::class;

    public function definition(): array
    {
        $what_to_add = $this->faker->randomElement(['nothing', 'next_things']);
        $what_to_remove = $this->faker->randomElement(['nothing', 'next_things']);
        return [
            'user_id' => User::factory(1)->create()->first()->id,
            'publication_id' => $publicationId = Publication::factory(1)->create()->first()->id,
            'author_id' => Publication::find($publicationId)->author->id,
            'like_material' => $this->faker->randomElement(['yes', 'no', 'neutral']),
            'enough_material' => $this->faker->randomElement(['enough', 'not_enough', 'too_many']),
            'what_to_add' => [
                'all_ok' => $this->faker->boolean,
                'options' => $this->faker->randomElements(['add_audio_video', 'add_images', 'add_text', 'make_webinar'], $this->faker->numberBetween(0, 4)),
            ],
            'what_to_remove' => [
                'all_ok' => $this->faker->boolean,
                'options' => $this->faker->randomElements(['not_interesting', 'less_audio', 'less_video', 'less_images', 'less_text', 'less_webinars'], $this->faker->numberBetween(0, 6)),
            ],
        ];
    }
}
