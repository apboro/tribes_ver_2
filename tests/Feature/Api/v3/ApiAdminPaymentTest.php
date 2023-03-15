<?php

namespace Tests\Feature\Api\v3;

use App\Models\Administrator;
use App\Models\Payment;
use App\Models\User;
use App\Services\Pay\Entity\Pay;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiAdminPaymentTest extends TestCase
{
    use WithFaker;

    private $url = [
        'payments_list' => 'api/v3/manager/payments',
        'payment_customers' => 'api/v3/manager/payments/customers',
    ];


    private $data = [
        'empty_data' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'not_admin' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'payment_list_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [[
                    "order_id",
                    "community",
                    "add_balance",
                    "from",
                    "status",
                    "created_at",
                    "user_id",
                    "type",
                ]
                ]
            ],
        ],
        'payment_costomers_success'=>[
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [[
                    "id",
                    "name",
                    ]
                ]
            ],
        ]
    ];

    public function test_payments_list_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['payments_list']);

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }


    public function test_payments_list_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['payments_list']);

        $response->assertStatus($this->data['not_admin']['expected_status'])
            ->assertJsonStructure($this->data['not_admin']['expected_structure']);
    }


    public function test_payments_list_success()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();

        Payment::factory()->count(30)->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['payments_list']);

        $response->assertStatus($this->data['payment_list_success']['expected_status'])
            ->assertJsonStructure($this->data['payment_list_success']['expected_structure']);
    }


    public function test_payments_customers_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['payment_customers']);

        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }


    public function test_payments_customers_not_admin()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['payment_customers']);

        $response->assertStatus($this->data['not_admin']['expected_status'])
            ->assertJsonStructure($this->data['not_admin']['expected_structure']);
    }


    public function test_payments_customers_success()
    {
        $admin = new Administrator();
        $admin->user_id = $this->custom_user->id;
        $admin->save();
        User::factory()->count(50)->create();
        for ($z = 0; $z < 30; $z++) {
            $amount = rand(100, 500);

            Payment::create([
                'OrderId' => Str::random(15),
                'community_id' => null,
                'add_balance' => $amount,
                'from' => $this->faker->name,
                'comment' => $this->faker->text(300),
                'isNotify' => array_rand([0, 1]),
                'telegram_user_id' => null,
                'paymentId' => rand(30, 365),
                'amount' => $amount * 100,
                'paymentUrl' => env('APP_DOMAIN'),
                'response' => $this->faker->text(600),
                'status' => array_rand(Payment::$status),
                'token' => $this->faker->text(600),
                'error' => $this->faker->text(255),
                'type' => Payment::$types[array_rand(Payment::$types)],
                'activated' => array_rand([0, 1]),

                'SpAccumulationId' => $this->faker->text(255),
                'RebillId' => $this->faker->text(255),

                'user_id' => rand(1,50),
                'payable_id' => null,
                'payable_type' => null,
                'author' => null,
            ]);
        }

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['payment_customers']);

        $response->assertStatus($this->data['payment_costomers_success']['expected_status'])
            ->assertJsonStructure($this->data['payment_costomers_success']['expected_structure']);
    }
}
