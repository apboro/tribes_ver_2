<?php

namespace Tests\Feature\Api\v3;

use App\Models\User;
use App\Repositories\Notification\Sms16Repository;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiPhoneControllerTest extends TestCase
{

    use WithFaker;

    private $url = [
     'reset_confirmed'=>'api/v3/user/phone/reset-confirmed',
     'confirm_phone'=>'api/v3/user/phone/send-confirm-code',
    ];

    private $data = [
        'empty_data' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ]
        ],
        'success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
            ]
        ],
        'confirm_error_phone_empty' => [
            'phone'=>'',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'errors'
            ]
        ],
        'confirm_error_phone_not_valid' => [
            'phone'=>'qwe',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'errors'
            ]
        ],
        'success_send_code'=>[
            'phone'=>123456,
            'code'=>123,
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload'
            ]
        ]
    ];

    public function test_rest_confirmed_unauth()
    {
        $response =  $this->withHeaders([
            'Accept'=>'application/json',
        ])->get($this->url['reset_confirmed'], $this->data['empty_data']);
        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }

    public function test_reset_confirmed_success()
    {
        $user = User::create([
            'name'=>'test',
            'email'=>$this->faker->unique()->safeEmail(),
            'password'=>bcrypt('123456789'),
            'phone_confirmed'=>true,
            'phone'=>$this->faker->unique()->e164PhoneNumber(),
            'code'=>'1234'
        ]);
        $token = $user->createToken('api-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->get($this->url['reset_confirmed']);

        $user_after_update = User::where('id','=',$user->id)->first();

        $response->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);

        $this->assertFalse($user_after_update->phone_confirmed);
        $this->assertNull($user_after_update->code);
        $this->assertEquals('-',$user_after_update->phone);
    }


    public function test_confirm_unauth()
    {
        $response =  $this->withHeaders([
            'Accept'=>'application/json',
        ])->post($this->url['confirm_phone'], $this->data['empty_data']);
        $response->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }

    public function test_confirm_error_phone_empty()
    {
        $user = User::create([
            'name'=>'test',
            'email'=>$this->faker->unique()->safeEmail(),
            'password'=>bcrypt('123456'),
            'phone_confirmed'=>false,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;
        $response = $this->withHeaders([
            'Accept'=>'application/json',
            'Authorization' => 'Bearer ' . $token
        ])->post($this->url['confirm_phone'],$this->data['confirm_error_phone_empty']);
        $response->assertStatus($this->data['confirm_error_phone_empty']['expected_status'])
            ->assertJsonStructure($this->data['confirm_error_phone_empty']['expected_structure']);
    }

    public function test_confirm_error_phone_not_valid()
    {
        $user = User::create([
            'name'=>'test',
            'email'=>$this->faker->unique()->safeEmail(),
            'password'=>bcrypt('123456'),
            'phone_confirmed'=>false,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;
        $response = $this->withHeaders([
            'Accept'=>'application/json',
            'Authorization' => 'Bearer ' . $token
        ])->post($this->url['confirm_phone'],$this->data['confirm_error_phone_not_valid']);

        $response->assertStatus($this->data['confirm_error_phone_not_valid']['expected_status'])
            ->assertJsonStructure($this->data['confirm_error_phone_not_valid']['expected_structure']);
    }

    public function test_send_code_succeess(){
        $user = User::create([
            'name'=>'test',
            'email'=>$this->faker->unique()->safeEmail(),
            'password'=>bcrypt('123456'),
            'phone_confirmed'=>false,
        ]);
        $this->mock(Sms16Repository::class)
            ->shouldReceive('sendConfirmationTo')
            ->once()
            ->andReturn([
                    [
                        $this->data['success_send_code']['code'].$this->data['success_send_code']['phone']=>[
                            'error'=>0
                        ]
                    ]
            ]);

        $token = $user->createToken('api-token')->plainTextToken;
        $response = $this->withHeaders([
            'Accept'=>'application/json',
            'Authorization' => 'Bearer ' . $token
        ])->post($this->url['confirm_phone'],$this->data['success_send_code']);
        $response->assertStatus($this->data['success_send_code']['expected_status'])
            ->assertJsonStructure($this->data['success_send_code']['expected_structure']);
    }
}
