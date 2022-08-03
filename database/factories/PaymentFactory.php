<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @method Payment getItemByAttrs(array $attributes)
 */
class PaymentFactory extends Factory
{
    use instanceTrait;
    protected static $items = [];

    public function definition()
    {
        $amount = rand(100,500);

        return [
            //'id' => null,
            'OrderId' => Str::random(15),
            'community_id' => null,
            'add_balance' => $amount,
            'from' => null,
            'comment' => $this->faker->text(300),
            'isNotify' => array_rand([0,1]),
            'telegram_user_id' => null,
            'paymentId' => rand(30, 365),
            'amount' => $amount * 100,
            'paymentUrl' => env('APP_DOMAIN'),
            'response' => $this->faker->text(600),
            'status' => array_rand(Payment::$status),
            'token' => $this->faker->text(600),
            'error' => $this->faker->text(255),
            'type' => Payment::$types[array_rand(Payment::$types)],
            'activated' => array_rand([0,1]),

            'SpAccumulationId' => $this->faker->text(255),
            'RebillId' => $this->faker->text(255),

            'user_id' => null,
            'payable_id' => null,
            'payable_type' => null,
            'author' => null,

        ];
    }

    public function typeDonate($id): self
    {
        return $this->state(function (array $attributes) use ($id) {
            return [
                'payable_id' => $id,
                'payable_type' => 'App\Models\DonateVariant',
            ];
        });
    }

    public function typeCourse($id): self
    {
        return $this->state(function (array $attributes) use ($id) {
            return [
                'payable_id' => $id,
                'payable_type' => 'App\Models\Course',
            ];
        });
    }

}
