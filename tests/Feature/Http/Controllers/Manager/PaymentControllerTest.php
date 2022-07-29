<?php

namespace Tests\Feature\Http\Controllers\Manager;

use App\Models\Administrator;
use App\Models\Community;
use App\Models\Payment;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    public function testGetListPayments()
    {
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $response = $this->postJson(route('manager.payments.list'));

        $response->assertStatus(200)
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
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $response = $this->postJson('api/v2/payments', ['search' => 'er 0']);

        $response->assertOk()
            ->assertJsonFragment([
                'from' => 'Buyer 0'
            ])
            ->assertValid()
            ->assertJsonCount('1', 'data');
    }

    public function testGetListPaymentsWithFilterByDate()
    {
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $date = Payment::findOrFail(1)->created_at;

        $response = $this->postJson('api/v2/payments', ['date' => $date->toDateString()]);

        $response->assertOk()
            ->assertJsonFragment([
                'created_at' => $date
            ])
            ->assertJsonCount('1', 'data');
    }

    public function testGetEmptyListPayments()
    {
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $response = $this->postJson('api/v2/payments', ['search' => 'Strange query', 'date' => '01.01.0001']);

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function testGetSortingListPaymentsByAscUserId()
    {
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $response = $this->postJson('api/v2/payments', ['sort' => ['name' => 'user', 'rule' => 'asc']]);

        $response->assertOk()
            ->assertValid()
            ->assertJsonCount(5, 'data');

        $content = json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
        $this->assertEquals($content, str_replace("\r", '', $this->getDataFromFile('Manager/payments.asc.json', true)), '');
    }

    public function testGetSortingListPaymentsByDescUserId()
    {
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $response = $this->postJson('api/v2/payments', ['sort' => ['name' => 'user', 'rule' => 'desc']]);

        $response->assertOk()
            ->assertJsonCount(5, 'data');

        $content = json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
        $this->assertEquals($content, str_replace("\r", '', $this->getDataFromFile('Manager/payments.desc.json', true)), '');

    }

    public function testGetSortingListPaymentsByAscDate()
    {
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $response = $this->postJson('api/v2/payments', ['sort' => ['name' => 'date', 'rule' => 'asc']]);

        $response->assertOk()
            ->assertJsonCount(5, 'data');

        $content = json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
        $this->assertEquals($content, str_replace("\r", '', $this->getDataFromFile('Manager/payments.asc.json', true)), '');
    }

    public function testGetSortingListPaymentsByDescDate()
    {
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $response = $this->postJson('api/v2/payments', ['sort' => ['name' => 'date', 'rule' => 'desc']]);

        $response->assertOk()
            ->assertJsonCount(5, 'data');

        $content = json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
        $this->assertEquals($content, str_replace("\r", '', $this->getDataFromFile('Manager/payments.desc.json', true)), '');
    }

    public function testGetWrongSortingListPaymentsByUserId()
    {
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $response = $this->postJson('api/v2/payments', ['sort' => ['name' => 'date', 'rule' => '']]);

        $response->assertOk()
            ->assertJsonCount(5, 'data');

        $content = json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
        $this->assertNotEquals($content, str_replace("\r", '', $this->getDataFromFile('Manager/payments.asc.json', true)), '');
    }

    public function testGetWrongSortingListPaymentsByDate()
    {
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $response = $this->postJson('api/v2/payments', ['sort' => ['name' => 'user', 'rule' => '']]);

        $response->assertOk()
            ->assertJsonCount(5, 'data');

        $content = json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT);
        $this->assertNotEquals($content, str_replace("\r", '', $this->getDataFromFile('Manager/payments.asc.json', true)), '');
    }

    public function testError401()
    {
        $response = $this->postJson('api/v2/payments');

        $response->assertUnauthorized();
    }

    public function testNotAdmin()
    {
        $this->AuthSanctum(User::factory()->create());

        $response = $this->postJson('api/v2/payments');

        $response->assertRedirect('/');
    }

    private function AuthSanctum($user)
    {
        Sanctum::actingAs(
            $user
        );
    }

    private function CreatePayments()
    {
        $user = User::factory()->has(Community::factory()->state([
            'title' => 'Test'
        ]))->create();

        Administrator::factory()->create([
            'user_id' => $user
        ]);

        $community = $user->communities()->first();

        Sanctum::actingAs(
            $user
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

        return $user;
    }
}
