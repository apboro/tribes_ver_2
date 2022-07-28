<?php

namespace Database\Factories;

use App\Models\Tariff;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @method Tariff getItemByAttrs(array $attributes)
 */
class TariffFactory extends Factory
{
    public function definition()
    {
        return [
            //'id' => null,
            'community_id' => null,
            'test_period' => 0,
            'title' => $this->faker->text(255),
            'main_description' => $this->faker->text(600),
            'main_image_id' => rand(125,5000),
            'welcome_description' => $this->faker->text(600),
            'welcome_image_id' => rand(125,5000),
            'reminder_description' =>  $this->faker->text(600),
            'reminder_image_id' => rand(125,5000),
            'thanks_description' => $this->faker->text(600),
            'thanks_image_id' => rand(125,5000),
            'tariff_notification' => array_rand([0,1]),
            'publication_description' => $this->faker->text(600),
            'publication_image_id' => rand(125,5000),

        ];
    }

    public function testPeriod($count): self
    {
        return $this->state(function (array $attributes) use ($count) {
            return [
                'test_period' => $count,
            ];
        });
    }

    public function tariffNotification(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'tariff_notification' => true,
            ];
        });
    }
}
