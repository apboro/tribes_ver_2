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
        $data = $this->prepareDB();
        $hash = $data;
        $response = $this->get(route('course.pay',compact($hash)),[

        ]);

        $response->assertStatus(200);
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
        $donateVariant = DonateVariant::factory()
            ->create([
                'donate_id' => $donate->id,
                'isStatic' => 1,
                'isActive' => 1,
                'description' => 'test donate variant',
                'index' => 0,
                'price' => 1000,
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
