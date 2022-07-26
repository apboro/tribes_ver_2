<?php

namespace Tests\Feature\Http\Controllers\Manager;

use App\Models\Community;
use App\Models\Payment;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    public function testGetListPayments()
    {
        $this->AuthSanctum();

        $this->postJson('api/v2/payments')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'OrderId',
                        'community',
                        'add_balance',
                        'created_at',
                        'type',
                    ]
                ]
            ]);
    }

    public function testGetListPaymentsWithFilterByFromSearch()
    {
        $this->AuthSanctum();

        $this->CreatePayments();

        $this->postJson('api/v2/payments', ['search' => 'er 0'])
            ->assertOk()
            ->assertJsonFragment([
                'from' => 'Buyer 0'
            ])
            ->assertValid()
            ->assertJsonCount('1', 'data');
    }

    public function testGetListPaymentsWithFilterByDate()
    {
        $this->AuthSanctum();

        $this->CreatePayments();

        $date = Payment::findOrFail(1)->created_at;

        $this->postJson('api/v2/payments', ['date' => $date->toDateString()])
            ->assertOk()
            ->assertJsonFragment([
                'created_at' => $date
            ])
            ->assertJsonCount('1', 'data');
    }

    public function testGetEmptyListPayments()
    {
        $this->AuthSanctum();

        $this->postJson('api/v2/payments', ['search' => 'Strange query', 'date' => '01.01.0001'])
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function testGetSortingListPaymentsByAscUserId()
    {
        $this->AuthSanctum();

        $this->CreatePayments();

        $response = $this->postJson('api/v2/payments', ['sort' => ['name' => 'user', 'rule' => 'asc']])
            ->assertOk()
            ->assertValid()
            ->assertJsonCount(5, 'data');

        $content = json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
        $this->assertEquals($content, str_replace("\r", '', $this->getDataFromFile('Manager/payments.asc.json', true)), '');
    }

    public function testGetSortingListPaymentsByDescUserId()
    {
        $this->AuthSanctum();

        $this->CreatePayments();

        $response = $this->postJson('api/v2/payments', ['sort' => ['name' => 'user', 'rule' => 'desc']])
            ->assertOk()
            ->assertJsonCount(5, 'data');

        $content = json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
        $this->assertEquals($content, str_replace("\r", '', $this->getDataFromFile('Manager/payments.desc.json', true)), '');

    }

    public function testGetSortingListPaymentsByAscDate()
    {
        $this->AuthSanctum();

        $this->CreatePayments();

        $response = $this->postJson('api/v2/payments', ['sort' => ['name' => 'date', 'rule' => 'asc']])
            ->assertOk()
            ->assertJsonCount(5, 'data');

        $content = json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
        $this->assertEquals($content, str_replace("\r", '', $this->getDataFromFile('Manager/payments.asc.json', true)), '');
    }

    public function testGetSortingListPaymentsByDescDate()
    {
        $this->AuthSanctum();

        $this->CreatePayments();

        $response = $this->postJson('api/v2/payments', ['sort' => ['name' => 'date', 'rule' => 'desc']])
            ->assertOk()
            ->assertJsonCount(5, 'data');

        $content = json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
        $this->assertEquals($content, str_replace("\r", '', $this->getDataFromFile('Manager/payments.desc.json', true)), '');
    }

    public function testGetWrongSortingListPaymentsByUserId()
    {
        $this->AuthSanctum();

        $this->CreatePayments();

        $response = $this->postJson('api/v2/payments', ['sort' => ['name' => 'date', 'rule' => '']])
            ->assertOk()
            ->assertJsonCount(5, 'data');

        $content = json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
        $this->assertNotEquals($content, str_replace("\r", '', $this->getDataFromFile('Manager/payments.asc.json', true)), '');
    }

    public function testGetWrongSortingListPaymentsByDate()
    {
        $this->AuthSanctum();

        $this->CreatePayments();

        $response = $this->postJson('api/v2/payments', ['sort' => ['name' => 'user', 'rule' => '']])
            ->assertOk()
            ->assertJsonCount(5, 'data');

        $content = json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
        $this->assertNotEquals($content, str_replace("\r", '', $this->getDataFromFile('Manager/payments.asc.json', true)), '');
    }

    public function testError401()
    {
        $this->postJson('api/v2/payments')
            ->assertUnauthorized();
    }

    private function AuthSanctum()
    {
        Sanctum::actingAs(
            User::factory()->create()
        );
    }

    private function CreatePayments()
    {
        $community = Community::factory()->create(
            ['title' => 'Test']
        );

        for ($i = 0; $i < 5; $i++) {
            Payment::factory()->create([
                'id' => $i + 1,
                'OrderId' => 'q6CibHWjsf15Ti' . $i,
                'community_id' => $community->id,
                'add_balance' => $i,
                'from' => 'Buyer ' . $i,
                'comment' => 'Nulla',
                "isNotify" => true,
                'paymentId' => $i,
                'amount' => 100 + $i,
                'response' => 'Natus',
                'status' => 'NEW',
                'token' => 'Voluptatum',
                'error' => 'Rerum',
                'created_at' => '2022-07-1' . $i . 'T07:34:16.000000Z',
                'updated_at' => '2022-07-1' . $i . 'T07:34:16.000000Z',
                'type' => 'tariff',
                'activated' => true,
                'SpAccumulationId' => 'Necessitatibus',
                'RebillId' => 'Est',
                'user_id' => $i,
            ]);
        }
    }
}
