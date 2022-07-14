<?php

namespace Tests\Feature\Payment;

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
        /*
         * INSERT INTO "payments" (
         *      "paymentId", "amount", "paymentUrl", "response", "status", "token", "error", "created_at", "updated_at", "type", "activated", "SpAccumulationId", "RebillId", "user_id", "payable_id", "payable_type", "author")
         *      1398083450,	1000,	'https://securepayments.tinkoff.ru/ClVrMhln                                                                                                                                                                                                                     ',	'deprecated',	'CONFIRMED',	'1d0ebea552eb43d0b1e1561f6de8ae92e3de7f1abec52399244d1caed7dbdfa6',	'0                                                                                                                                                                                                                                                              ',	'2022-06-08 00:50:50',	'2022-06-08 00:51:05',	'course',	'f',	'1595642',	NULL,	78,	44,	'App\Models\Course',	37);  */                                                                                                                                                                                                                   ;deprecated;CONFIRMED;1d0ebea552eb43d0b1e1561f6de8ae92e3de7f1abec52399244d1caed7dbdfa6;"0                                                                                                                                                                                                                                                              ";2022-06-08 00:50:50;2022-06-08 00:51:05;course;f;1595642;;78;44;App\Models\Course;37
        Payment::factory()->create([
            'id' => 154,
            'OrderId' => '154_0608_50',
            'community_id' => null,
            'add_balance' => 10,
            'from' => 'joinourtribes+50332media',
            'comment' => $this->faker->text(300),
            'isNotify' => false,
            'telegram_user_id' => null,
            'paymentId' => 1398083450,
            'amount' => 1000,
            'paymentUrl' => 'https://securepayments.tinkoff.ru/ClVrMhln',
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
        ]);

        $response = $this->post('/tinkoff/notify',$this->getDataFromFile('tinkoff/notify_payment_154_0608_50_C.json'));

        $response->assertStatus(200);
        $this->assertTrue(
            $this->getTestHandler()->hasRecord('NOTY: Платёж с OrderId 154_0608_50 и PaymentId 1398083450 не найден', 'debug'),
            'Не привязан к событию \App\Services\Telegram\MainComponents\KnowledgeObserver::detectUserQuestion'
        );

    }
}
