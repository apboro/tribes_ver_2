<?php

namespace Tests\Feature\Api\v3;

use App\Http\ApiResources\ApiDonatesResource;
use App\Models\Donate;
use Tests\TestCase;

class ApiDonatesTest extends TestCase
{
    private $urls = [
        'list' => 'api/v3/donates',
        'store' => 'api/v3/donate',
        'show' => 'api/v3/donate',
        'delete' => 'api/v3/donate',
        'put' => 'api/v3/donate',
        'pay' => 'api/v3/pay/donate',
    ];

    private $data = [
        "title" => "Donate 111",
        "description" => "My lovely donate",
        "image" => "/storage/test.jpg",
        "donate_is_active" => true,
        "random_sum_is_active" => true,
        "random_sum_min" => 50,
        "random_sum_max" => 5000,
        "fix_sum_1_is_active" => true,
        "fix_sum_2_is_active" => true,
        "fix_sum_3_is_active" => true,
        "fix_sum_1" => 500,
        "fix_sum_2" => 1000,
        "fix_sum_3" => 10000,
        "fix_sum_1_button" => "na iriski",
        "fix_sum_2_button" => "na kotletki",
        "fix_sum_3_button" => "na samolet",
        "random_sum_button" => "na doktorskuyu",
    ];

    public function testStoreDonate()
    {
        $this->data['user_id'] = $this->custom_user->id;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->urls['store'], $this->data);

        $response->assertStatus(200);
    }

    public function testDonateList()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->urls['list']);

        $response->assertStatus(200);
    }

    public function testStoreDonatesUnauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->urls['store']);
        $response->assertStatus(401);
    }

    public function testGetDonate()
    {
        $donate = Donate::first();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->urls['show'] . '/' . $donate->id);

        $response->assertStatus(200);
    }

    public function testDeleteDonate()
    {
        $donate = Donate::first();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->delete($this->urls['delete'] . '/' . $donate->id);

        $response->assertStatus(200);
    }

    public function testPutDonate()
    {
        $donate = Donate::first();
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->urls['put'] . '/' . $donate->id, $this->data);

        $response->assertStatus(200);
    }

    public function testPayDonate()
    {
        $donate = Donate::first();
        $data_pay = [
            "amount" => "100",
            "telegram_user_id" => $this->custom_telegram_user->telegram_id,
            "donate_id" => $donate->id,
        ];
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->urls['pay'], $data_pay);

        $response->assertStatus(302);

    }

}
