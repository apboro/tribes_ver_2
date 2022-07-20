<?php

namespace Tests\Feature\Payment;

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
        $response = $this->get('/payment/donate/range');

        $response->assertStatus(200);
    }

    public function testStaticPay()
    {
        $hash = '';
        $response = $this->get("/payment/donate/$hash");

        $response->assertStatus(200);
    }
}
