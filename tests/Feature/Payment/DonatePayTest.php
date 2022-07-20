<?php

namespace Tests\Feature\Payment;

use App\Models\Community;
use App\Models\Donate;
use App\Models\DonateVariant;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DonatePayTest extends TestCase
{
    /**
     *
     *
     * @return void
     */
    public function testRangePay()
    {
        $data = $this->prepareDB();
        $response = $this->get(route('payment.donate.range'),[
            'amount' => '',
            'currency' => '',
            'donateId' => '',
        ]);
        $response->assertStatus(200);
    }

    public function testStaticPay()
    {
        $data = $this->prepareDB();
        $hash = $data['community']['hash'];
        /** @var Community $community */
        $community = $data['community_object'];
        $link = $community->getDonatePaymentLink([
            'amount' => '10',
            'currency' => '0',
            'donateId' => $data['donate']['id'],
        ]);
        $response = $this->get($link);
        $response->assertStatus(302);
        $response->assertRedirect('//ya.ru');

        $this->assertDatabaseHas(Payment::class, [
            "type" => "donate",
            "amount" => 1000,
            "from" => 'Анонимный пользователь',
            "community_id" => $data['community']['id'],
            "author" => $data['community']['owner'],
            "add_balance" => 10,
        ]);
    }

    protected function prepareDB()
    {
        $data = $this->prepareDBCommunity();
        $donate = Donate::factory()->create([
            'community_id' => $data['community']['id'],
            'description'	 => "a",
            'isSendToCommunity'	 => 1,
            //'inline_link'	 => Str::random(8),
            'prompt_image_id'	 => "0",
            'isAutoPrompt'	 => false,
            'title'	 => 'test donate',
            'index' => 1,
        ]);
        //todo пока не получается подменить ответ тинькоффа, потому все платежи должны быть размером в 1000
        $donateVariant = DonateVariant::factory()
            ->create([
                'donate_id' => $donate->id,
                'isStatic' => 1,
                'isActive' => 1,
                'description' => 'test donate variant',
                'index' => 0,
                'price' => 10,
                'min_price' => null,
                'max_price' => null,
                'currency' => 0
            ]);
        return array_merge($data, [
            'donate' => $donate->getAttributes(),
            'donateVariant' => $donateVariant->getAttributes()
        ]);
    }
}
