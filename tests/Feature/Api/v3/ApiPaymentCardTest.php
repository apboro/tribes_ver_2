<?php

namespace Tests\Feature\Api\v3;

use App\Services\TinkoffE2C;
use Tests\TestCase;

class ApiPaymentCardTest extends TestCase
{

    private $url = [
        'add_card' => 'api/v3/payment-cards',
        'delete_card' => 'api/v3/payment-cards',
        'card_list' => 'api/v3/payment-cards',
    ];

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'api_customer_not_identify' => [
            'expected_status' => 400,
            'expected_structure' => [
                'message',
            ],
        ],
        'create_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [
                    'status',
                    'customer_key',
                    'message',
                    'details',
                    'redirect',
                ],
            ],
        ],
        'delete_error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'delete_card_empty_card_id' => [
            'expected_status' => 422,
            'expected_structure' => [
                'message',
            ],
        ],
        'delete_api_customer_not_identify' => [
            'card_id' => '123',
            'expected_status' => 400,
            'expected_structure' => [
                'message',
            ],
        ],
        'delete_card_success' => [
            'card_id' => '123',
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [
                    'status',
                    'customer_key',
                    'message',
                    'details',
                    'redirect',
                ],
            ],
        ],
        'list_card_error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'list_api_customer_not_identify' => [
            'expected_status' => 400,
            'expected_structure' => [
                'message',
            ],
        ],
        'list_cards_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'data' => [
                    [
                        'status',
                        'customer_key',
                        'message',
                        'details',
                    ],
                ],
            ],
        ],
    ];

    public function test_cards_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['add_card']);
        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_cards_check_customer_error()
    {

        $this->mock(TinkoffE2C::class)
            ->shouldReceive('checkCustomer')
            ->once()
            ->andReturn(false);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['add_card']);
        $response->assertStatus($this->data['api_customer_not_identify']['expected_status'])
            ->assertJsonStructure($this->data['api_customer_not_identify']['expected_structure']);
    }

    public function test_cards_recive_response()
    {
        $mockTink = $this->mock(TinkoffE2C::class);
        $mockTink->shouldReceive('checkCustomer')
            ->once()
            ->andReturn(true);

        $mockTink
            ->shouldReceive('AddCard')
            ->once()
            ->andReturn(true);

        $mockTink
            ->shouldReceive('response')
            ->once()
            ->andReturn([
                'data' => 'test',
                'status' => 'test',
                'customer_key' => 'test',
                'message' => 'test',
                'details' => 'test',
                'paymentUrl' => 'test',
            ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['add_card']);
        $response->assertStatus($this->data['create_success']['expected_status'])
            ->assertJsonStructure($this->data['create_success']['expected_structure']);
    }

    public function test_delete_card_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete($this->url['delete_card']);
        $response->assertStatus($this->data['delete_error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['delete_error_not_auth']['expected_structure']);
    }

    public function test_delete_card_empty_card_id()
    {

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->delete($this->url['delete_card'], $this->data['delete_card_empty_card_id']);

        $response->assertStatus($this->data['delete_card_empty_card_id']['expected_status'])
            ->assertJsonStructure($this->data['delete_card_empty_card_id']['expected_structure']);
    }

    public function test_delete_card_check_customer_error()
    {

        $this->mock(TinkoffE2C::class)
            ->shouldReceive('checkCustomer')
            ->once()
            ->andReturn(false);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->delete($this->url['delete_card'], $this->data['delete_api_customer_not_identify']);

        $response->assertStatus($this->data['delete_api_customer_not_identify']['expected_status'])
            ->assertJsonStructure($this->data['delete_api_customer_not_identify']['expected_structure']);
    }

    public function test_delete_card_success()
    {
        $mockTink = $this->mock(TinkoffE2C::class);
        $mockTink->shouldReceive('checkCustomer')
            ->once()
            ->andReturn(true);

        $mockTink
            ->shouldReceive('RemoveCard')
            ->once()
            ->andReturn(true);

        $mockTink
            ->shouldReceive('response')
            ->once()
            ->andReturn([
                'data' => 'test',
                'status' => 'test',
                'customer_key' => 'test',
                'message' => 'test',
                'details' => 'test',
                'paymentUrl' => 'test',
            ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->delete($this->url['delete_card'], $this->data['delete_card_success']);
        $response->assertStatus($this->data['delete_card_success']['expected_status'])
            ->assertJsonStructure($this->data['delete_card_success']['expected_structure']);
    }

    public function test_list_cards_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['card_list']);
        $response->assertStatus($this->data['list_card_error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['list_card_error_not_auth']['expected_structure']);
    }

    public function test_list_cards_check_customer_error()
    {

        $this->mock(TinkoffE2C::class)
            ->shouldReceive('checkCustomer')
            ->once()
            ->andReturn(false);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['card_list']);
        $response->assertStatus($this->data['list_api_customer_not_identify']['expected_status'])
            ->assertJsonStructure($this->data['list_api_customer_not_identify']['expected_structure']);
    }

    public function test_list_cards_success()
    {
        $mockTink = $this->mock(TinkoffE2C::class);
        $mockTink->shouldReceive('checkCustomer')
            ->once()
            ->andReturn(true);

        $mockTink
            ->shouldReceive('GetCardList')
            ->once()
            ->andReturn(true);

        $mockTink
            ->shouldReceive('response')
            ->once()
            ->andReturn([
                'data' => [
                    [
                        'status' => 'test',
                        'customer_key' => 'test',
                        'message' => 'test',
                        'details' => 'test',
                        'paymentUrl' => 'test',
                    ],
                    [
                        'status' => 'test2',
                        'customer_key' => 'test2',
                        'message' => 'test2',
                        'details' => 'test2',
                    ],
                ],
            ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['delete_card'], $this->data['list_cards_success']);

        $response->assertStatus($this->data['list_cards_success']['expected_status'])
            ->assertJsonStructure($this->data['list_cards_success']['expected_structure']);
    }

}
