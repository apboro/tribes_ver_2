<?php

namespace Tests\Feature\Payment;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CoursePayTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPay()
    {
        $response = $this->get('/course/pay');

        $response->assertStatus(200);
    }
}
