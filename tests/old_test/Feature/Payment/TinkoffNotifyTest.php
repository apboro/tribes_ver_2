<?php

namespace Tests\old_test\Feature\Payment;

use App\Models\Payment;
use Tests\TestCase;

class TinkoffNotifyTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNotifyConfirm()
    {
        $data = $this->prepareDBCommunity();

        $payment = Payment::factory()->create([
            'id' => 154,
            'OrderId' => '154_0608_50',
            'community_id' => $data['community']['id'],
            'add_balance' => 10,
            'from' => 'joinourtribes+50332media',
            'comment' => '',
            'isNotify' => false,
            'telegram_user_id' => null,
            'paymentId' => 1398083450,
            'amount' => 1000,
            'paymentUrl' => 'https://securepayments.tinkoff.ru/ClVrMhln',
            'response' => 'deprecated',
            'status' => 'NEW',
            'token' => '1d0ebea552eb43d0b1e1561f6de8ae92e3de7f1abec52399244d1caed7dbdfa6',
            'error' => '0',
            'type' => 'course',
            'activated' => false,
            'SpAccumulationId' => '1595642',
            'RebillId' => null,

            'user_id' => 78,
            'payable_id' => 44,
            'payable_type' => 'App\Models\Course',
            'author' => $data['user']['id'],
        ]);

        $response = $this->post('/tinkoff/notify',$this->getDataFromFile('tinkoff/notify_payment_154_0608_50_C.json'));

        $response->assertStatus(200);
        $this->assertFalse(
            $this->getTestHandler()->hasRecord('NOTY: Платёж с OrderId 154_0608_50 и PaymentId 1398083450 не найден', 'debug'),
            ''
        );
        $this->assertDatabaseHas(Payment::class,[
            'id' =>$payment->id,
            'status' => "CONFIRMED",
        ]);
    }
}
