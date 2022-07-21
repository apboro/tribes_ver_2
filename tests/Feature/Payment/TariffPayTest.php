<?php

namespace Tests\Feature\Payment;

use App\Helper\ArrayHelper;
use App\Models\Payment;
use App\Models\Tariff;
use App\Models\TariffVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TariffPayTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testFormPay()
    {
        $data = $this->prepareDB();
        $response = $this->post(
            route('community.tariff.form', ['community' => $data['community']['id']]),
            [
                'email' => 'new-user@ya.ru',
                'communityTariffID' => $data['tariffVariant']['id'],
            ]
        );

        $response->assertStatus(302);
        $response->assertRedirect('//ya.ru');

        $this->assertDatabaseHas(Payment::class, [
            "type" => "tariff",
            "amount" => 9900,
            "from" => "new-user",
            "community_id" => $data['community']['id'],
            "author" => $data['community']['owner'],
            "add_balance" => 99,
        ]);
    }

    protected function prepareDB()
    {
        $data = $this->prepareDBCommunity();
        $tariff = Tariff::factory()->tariffNotification()->testPeriod(20)->create([
            'community_id' => $data['community']['id'],
        ]);
        $tariffVariant = TariffVariant::factory()->active()
            ->create([
                'price' => 99,
                'title' => 'Вариант для тарифа №1',
                'tariff_id' => $tariff->id,
            ]);
        return array_merge($data, [
            'tariff' => $tariff->getAttributes(),
            'tariffVariant' => $tariffVariant->getAttributes()
        ]);
    }
}
