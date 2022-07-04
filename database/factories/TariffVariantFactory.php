<?php

namespace Database\Factories;

use App\Models\TariffVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @method TariffVariant getItemByAttrs(array $attributes)
 */
class TariffVariantFactory extends Factory
{
    use instanceTrait;
    protected static $items = [];

    public function definition()
    {
        return [
            //'id' => null,
            'tariff_id' => null,
            'title' => $this->faker->text(255),
            'price' => rand(100,5000),
            'period' => rand(1,31),
            'isActive' => null,
        ];
    }

    public function period($count): self
    {
        return $this->state(function (array $attributes) use ($count) {
            return [
                'period' => $count,
            ];
        });
    }

    public function price($count): self
    {
        return $this->state(function (array $attributes) use ($count) {
            return [
                'price' => $count,
            ];
        });
    }

    public function active(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'isActive' => array_rand([0,1]),
            ];
        });
    }

    public function notActive(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'isActive' => false,
            ];
        });
    }
}
