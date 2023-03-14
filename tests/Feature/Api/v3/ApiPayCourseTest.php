<?php

namespace Api\v3;

use App\Helper\PseudoCrypt;
use App\Http\Controllers\APIv3\ApiCourseController;
use App\Models\Course;
use App\Models\User;
use App\Services\Tinkoff\Payment;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\TestCase;

class ApiPayCourseTest extends TestCase
{
    use WithFaker;

    private $url = [
        'pay_course' => 'api/v3/courses/pay',
    ];

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'error_empty_email' => [
            'email',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'errors',
                'payload'
            ]
        ],
        'error_email_not_valid' => [
            'email' => 'test',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'errors',
                'payload'
            ]
        ],
        'error_empty_hash' => [
            'email' => 'test',
            'expected_status' => 405,
        ],
        'course_not_exists' => [
            'hash' => 'test',
            'email' => 'test',
            'expected_status' => 404,
            'expected_structure' => [
                'message',
                'payload'
            ]
        ],
        'course_with_zero_cost_exist_user' => [
            'hash' => '',
            'email' => '',
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
                'data'=>[
                    'redirect'
                ]
            ]
        ],
        'course_with_cost_exist_user_payment_error' => [
            'hash' => '',
            'email' => '',
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
                'data'=>[
                    'redirect'
                ]
            ]
        ],
        'course_payment_error'=>[
            'hash' => '',
            'email' => '',
            'expected_status' => 400,
            'expected_structure' => [
                'message',
                'payload'=>[
                    'redirect'
                ],
            ]
        ],
        'course_payment_success'=>[
            'hash' => '',
            'email' => '',
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'data'=>[
                    'redirect'
                ],
            ]
        ]

    ];
/*
    public function test_pay_course_easy_register()
    {
        do {
            $email = $this->faker->safeEmail;
            $is_exists = User::where('email', '=', $email)->first();
        } while ($is_exists);
        $user = ApiCourseController::easy_register_user($email, 'test123');
        $this->assertEquals($user->email, $email);
    }

    public function test_pay_course_easy_register_already_exists()
    {
        $email = $this->custom_user->email;
        $user = ApiCourseController::easy_register_user($email, 'test123');
        $this->assertEquals($this->custom_user->id, $user->id);
        $this->assertEquals($user->email, $email);

    }

    public function test_pay_course_empty_email()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['pay_course'] . '/test');

        $response
            ->assertStatus($this->data['error_empty_email']['expected_status'])
            ->assertJsonStructure($this->data['error_empty_email']['expected_structure']);

    }


    public function test_pay_course_not_valid_email()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['pay_course'] . '/test', $this->data['error_email_not_valid']);
        $response
            ->assertStatus($this->data['error_email_not_valid']['expected_status'])
            ->assertJsonStructure($this->data['error_email_not_valid']['expected_structure']);

    }

    public function test_pay_course_empty_hash()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['pay_course'], $this->data['error_empty_hash']);

        $response
            ->assertStatus($this->data['error_empty_hash']['expected_status']);
    }

    public function test_pay_course_course_not_exists()
    {
        $this->data['course_not_exists']['email'] = $this->faker->safeEmail;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['pay_course'] . "/" . $this->data['course_not_exists']['hash'], $this->data['course_not_exists']);
        $response->assertStatus($this->data['course_not_exists']['expected_status'])
            ->assertJsonStructure($this->data['course_not_exists']['expected_structure']);
    }

    public function test_pay_course_with_zero_cost_exist_user()
    {
        $course = Course::create([
            'owner' => $this->custom_user->id,
            'title' => 'Новый курс'
        ]);
        $this->data['course_with_zero_cost_exist_user']['hash'] = PseudoCrypt::hash($course->id);
        $this->data['course_with_zero_cost_exist_user']['email'] = $this->custom_user->email;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['pay_course'] . "/" . $this->data['course_with_zero_cost_exist_user']['hash'], $this->data['course_with_zero_cost_exist_user']);
        $response->assertStatus($this->data['course_with_zero_cost_exist_user']['expected_status'])
            ->assertJsonStructure($this->data['course_with_zero_cost_exist_user']['expected_structure']);
    }


    public function test_pay_course_payment_error()
    {

        $course = Course::create([
            'owner' => $this->custom_user->id,
            'title' => 'Новый курс',
            'cost'  => 1000
        ]);

        $this->mock(Payment::class)
            ->shouldReceive('doPayment')
            ->once()
            ->andReturn(false);


        $this->data['course_payment_error']['hash'] = PseudoCrypt::hash($course->id);
        $this->data['course_payment_error']['email'] = $this->custom_user->email;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(
            $this->url['pay_course'] . "/" . $this->data['course_payment_error']['hash'],
            $this->data['course_payment_error']
        );

        $response->assertStatus($this->data['course_payment_error']['expected_status'])
            ->assertJsonStructure($this->data['course_payment_error']['expected_structure']);
    }
*/
    public function test_pay_course_payment_success()
    {
        $course = Course::create([
            'owner' => $this->custom_user->id,
            'title' => 'Новый курс',
            'cost'  => 1000
        ]);

        $this->mock(Payment::class)
            ->shouldReceive('doPayment')
            ->once()
            ->andReturn((object)[
                'paymentUrl'=>'test'
            ]);


        $this->data['course_payment_success']['hash'] = PseudoCrypt::hash($course->id);
        $this->data['course_payment_success']['email'] = $this->custom_user->email;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(
            $this->url['pay_course'] . "/" . $this->data['course_payment_success']['hash'],
            $this->data['course_payment_success']
        );
        $response->assertStatus($this->data['course_payment_success']['expected_status'])
            ->assertJsonStructure($this->data['course_payment_success']['expected_structure']);
    }

}
