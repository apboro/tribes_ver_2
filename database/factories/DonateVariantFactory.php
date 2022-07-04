<?php

namespace Database\Factories;

use App\Models\DonateVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @method DonateVariant getItemByAttrs(array $attributes)
 */
class DonateVariantFactory extends Factory
{
    use instanceTrait;
    protected static $items = [];

    public function definition()
    {
        return [
            //id => null,

            'donate_id' => null,
            'isStatic' => array_rand([0,1]),
            'isActive' => array_rand([0,1]),
            'description' => $this->faker->text(100),
            'index' => 0,
            'price' => null,
            'min_price' => null,
            'max_price' => null,
            'currency' => 0
        ];
    }
}
