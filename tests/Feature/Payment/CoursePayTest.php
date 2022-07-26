<?php

namespace Tests\Feature\Payment;

use App\Models\Course;
use App\Models\Payment;
use App\Services\SMTP\Mailer;
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
        $this->mock(Mailer::class)->shouldReceive();
        $data = $this->prepareDB();
        $course = $data['course'];
        $response = $this->post($course->payLink(), [
            'email' => 'adolgopolov@google.com',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('//ya.ru');

        $this->assertDatabaseHas(Payment::class, [
            "type" => "course",
            "amount" => 500000,
            "status" => "COMPLETE",
            "from" => "adolgopolov",
            "community_id" => $data['community']['id'],
            "author" => $data['community']['owner'],
            "add_balance" => 5000,
        ]);
    }

    protected function prepareDB()
    {
        $data = $this->prepareDBCommunity();
        $course = Course::factory()->create([
            'community_id' => $data['community']['id'],
            'title' => 'Test course ',
            'owner' => $data['community']['owner'],
            'description' => 'a',
            'isActive' => 1,
            'cost' => 5000,
            'access_days' => 30,
            'isPublished' => 1,
            'payment_title' => 'Опалата тестового курса',
            'payment_description' => 'фи',
            'isEthernal' => 0,
            'thanks_text' => 'Спасибо за покупку!',
            'shipping_noty' => 0,
            'shipping_views' => 10,
            'shipping_clicks' => 10,
            'views' => 300,
            'clicks' => 300,
            'shipped_count' => 300
        ]);

        return array_merge($data, [
            'course' => $course,
        ]);
    }
}
