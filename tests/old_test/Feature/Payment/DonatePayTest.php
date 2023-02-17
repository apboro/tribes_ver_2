<?php

namespace Tests\old_test\Feature\Payment;

use App\Models\Community;
use App\Models\Donate;
use App\Models\DonateVariant;
use App\Models\Payment;
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
        $hash = $data['community']['hash'];
        /** @var Community $community */
        $community = $data['community_object'];
        $link = $community->getDonatePaymentLink([
            'amount' => '85',
            'currency' => '0',
            'donateId' => $data['donate']['id'],
        ]);
        $response = $this->get($link);

        $response->assertStatus(302);

        $response->assertRedirect('//ya.ru');

        $this->assertDatabaseHas(Payment::class, [
            "type" => "donate",
            "amount" => 8500,
            "from" => 'Анонимный пользователь',
            "community_id" => $data['community']['id'],
            "author" => $data['community']['owner'],
            "add_balance" => 85,
        ]);
    }

    public function testStaticPay()
    {
        $data = $this->prepareDB();
        $hash = $data['community']['hash'];
        /** @var Community $community */
        $community = $data['community_object'];
        $link = $community->getDonatePaymentLink([
            'amount' => '9',
            'currency' => '0',
            'donateId' => $data['donate']['id'],
        ]);
        $response = $this->get($link);
        //dd($response->getContent());
        $response->assertStatus(302);
        $response->assertRedirect('//ya.ru');

        $this->assertDatabaseHas(Payment::class, [
            "type" => "donate",
            "amount" => 900,
            "from" => 'Анонимный пользователь',
            "community_id" => $data['community']['id'],
            "author" => $data['community']['owner'],
            "add_balance" => 9,
        ]);
    }

    /**
     создание хеша для тестового файла данных по платежу можно опираться только на путь и сумму платежа
     потому что все остальные параметры в $args являются динамическими,
     потому автотесты платежей разделять по Amount, каждый тест должен иметь свою сумму
     * @return array|string
     */
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

        $donateVariant = DonateVariant::factory()
            ->create([
                'donate_id' => $donate->id,
                'isStatic' => 1,
                'isActive' => 1,
                'description' => 'test donate variant',
                'index' => 0,
                'price' => 9,
                'min_price' => null,
                'max_price' => null,
                'currency' => 0
            ]);
        $donateVariantRange = DonateVariant::factory()
            ->create([
                'donate_id' => $donate->id,
                'isStatic' => 0,
                'isActive' => 1,
                'description' => 'test donate range variant',
                'index' => 0,
                'price' => 0,
                'min_price' => 80,
                'max_price' => 90,
                'currency' => 0
            ]);
        return array_merge($data, [
            'donate' => $donate->getAttributes(),
            'donateVariant' => $donateVariant->getAttributes(),
            'donateVariantRange' => $donateVariantRange->getAttributes(),
        ]);
    }
}
