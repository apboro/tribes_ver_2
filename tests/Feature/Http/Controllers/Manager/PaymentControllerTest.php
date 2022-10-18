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
                    ],
                ],
            ]);
    }

    public function testGetListPaymentsWithFilterByFromSearch()
    {
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $response = $this->postJson('api/v2/payments', ['filter' => ['search' => 'Buyer 0']]);

        $response->assertOk()
            ->assertJsonFragment([
                'from' => 'Buyer 0',
            ])
            ->assertValid()
            ->assertJsonCount('1', 'data');
    }

    public function testGetListPaymentsWithFilterByDate()
    {
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $date = Payment::findOrFail(1)->created_at;

        $response = $this->postJson('api/v2/payments', ['filter' => ['date' => $date->toDateString()]]);

        $response->assertOk()
            ->assertJsonFragment([
                'created_at' => $date,
            ])
            ->assertJsonCount('1', 'data');
    }

    public function testGetEmptyListPayments()
    {
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $response = $this->postJson('api/v2/payments', ['filter' => ['search' => 'Strange query', 'date' => '01.01.0001']]);

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function testGetSortingListPaymentsByAscUserId()
    {
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $response = $this->postJson('api/v2/payments', ['filter' => ['sort' => ['name' => 'user', 'rule' => 'asc']]]);
        $response->assertOk()
            ->assertValid()
            ->assertJsonCount(5, 'data');

        $content = json_decode($response->getContent());
        foreach ($content->data as $key => $item) {
            if ($key != 0) {
                $this->assertLessThan($item->user_id, $previous);
            }
            $previous = $item->user_id;
        }
    }

    public function testGetSortingListPaymentsByDescUserId()
    {
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $response = $this->postJson('api/v2/payments', ['filter' => ['sort' => ['name' => 'user', 'rule' => 'desc']]]);

        $response->assertOk()
            ->assertJsonCount(5, 'data');

        $content = json_decode($response->getContent());

        foreach ($content->data as $key => $item) {
            if ($key != 0) {
                $this->assertGreaterThan($item->user_id, $previous);
            }
            $previous = $item->user_id;
        }
    }

    public function testGetSortingListPaymentsByAscDate()
    {
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $response = $this->postJson('api/v2/payments',['filter' =>  ['sort' => ['name' => 'date', 'rule' => 'asc']]]);

        $response->assertOk()
            ->assertJsonCount(5, 'data');

        $content = json_decode($response->getContent());

        foreach ($content->data as $key => $item) {
            if ($key != 0) {
                $this->assertLessThan($item->created_at, $previous);
            }
            $previous = $item->created_at;
        }
    }

    public function testGetSortingListPaymentsByDescDate()
    {
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $response = $this->postJson('api/v2/payments', ['filter' =>  ['sort' => ['name' => 'date', 'rule' => 'desc']]]);

        $response->assertOk()
            ->assertJsonCount(5, 'data');

        $content = json_decode($response->getContent());
        foreach ($content->data as $key => $item) {
            if ($key != 0) {
                $this->assertGreaterThan($item->created_at, $previous);
            }
            $previous = $item->created_at;
        }
    }

    public function testGetWrongSortingListPaymentsByUserId()
    {
        $user = $this->CreatePayments();
        $this->AuthSanctum($user);

        $response = $this->postJson('api/v2/payments', ['filter' =>  ['sort' => ['name' => 'date', 'rule' => '']]]);

        $response->assertOk()
            ->assertJsonCount(5, 'data');

        $content = json_decode($response->getContent());
        foreach ($content->data as $key => $item) {
            if ($key != 0) {
                $this->assertGreaterThan($item->created_at, $previous);
            }
            $previous = $item->created_at;
        }
    }

    public function testError401()
    {
        $response = $this->postJson('api/v2/payments');

        $response->assertUnauthorized();
    }

    public function testNotAdmin()
    {
        $this->AuthSanctum($user = User::factory()->create());

        $response = $this->postJson('api/v2/payments');

        $response->assertUnauthorized();
        $this->assertEquals($response->getContent(),'{"message":"\u0432\u044b - \u043d\u0435 \u0430\u0434\u043c\u0438\u043d\u0438\u0441\u0442\u0440\u0430\u0442\u043e\u0440"}');
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
            'title' => 'Test',
        ]))->create();

        Administrator::factory()->create([
            'user_id' => $user,
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
