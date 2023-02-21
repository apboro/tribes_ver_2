<?php

namespace Tests\Feature\Api\v3;

use App\Models\SmsConfirmations as SmsConfirmation;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiConfirmPhoneTest extends TestCase
{
    use WithFaker;

    private $url = 'api/v3/user/phone/confirm';

    private $data = [
        'not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'empty_data' => [
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'errors' => ['sms_code'],
                'payload',
            ],
        ],
        'not_valid' => [
            'sms_code' => 'test',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'errors' => ['sms_code'],
                'payload',
            ],
        ],
        'sms_is_blocked' => [
            'sms_code' => '1234',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'errors' => ['sms_code'],
                'payload',
            ],
        ],
        'error_code' => [
            'sms_code' => '4321',
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'errors' => ['sms_code'],
                'payload',
            ],
        ],
        'success' => [
            'sms_code' => '1234',
            'expected_status' => 200,
            'expected_structure' => [
                'message',
                'payload',
            ],
        ],

    ];

    public function test_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url, $this->data['not_auth']);

        $response
            ->assertStatus($this->data['not_auth']['expected_status'])
            ->assertJsonStructure($this->data['not_auth']['expected_structure']);
    }

    public function test_empty_data()
    {

        $user = User::factory()->create();

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->post($this->url, $this->data['empty_data']);

        $response
            ->assertStatus($this->data['empty_data']['expected_status'])
            ->assertJsonStructure($this->data['empty_data']['expected_structure']);
    }

    public function test_not_valid_code()
    {
        $user = User::factory()->create();

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->post($this->url, $this->data['not_valid']);

        $response
            ->assertStatus($this->data['not_valid']['expected_status'])
            ->assertJsonStructure($this->data['not_valid']['expected_structure']);
    }

    public function test_sms_is_blocked()
    {
        $phone = $this->faker->unique()->e164PhoneNumber();

        $user = User::create([
            'name' => 'test',
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('123456789'),
            'phone_confirmed' => false,
            'phone' => $phone,
            'code' => '1234',
        ]);

        $sms_confirmation = SmsConfirmation::create([
            'user_id' => $user->id,
            'phone' => $phone,
            'code' => $user->code,
            'status' => 'OK',
            'isblocked' => true,
            'sms_id' => '1234',
            'cost' => '1',
            'ip' => request()->ip(),
        ]);

        $sms_confirmation->save();

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->post($this->url, $this->data['sms_is_blocked']);

        $user_after = User::where('id', '=', $user->id)->first();

        $response
            ->assertStatus($this->data['sms_is_blocked']['expected_status'])
            ->assertJsonStructure($this->data['sms_is_blocked']['expected_structure']);
    }

    public function test_sms_error_code()
    {
        $phone = $this->faker->unique()->e164PhoneNumber();

        $user = User::create([
            'name' => 'test',
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('123456789'),
            'phone_confirmed' => false,
            'phone' => $phone,
            'code' => '1234',
        ]);

        $sms_confirmation = SmsConfirmation::create([
            'user_id' => $user->id,
            'phone' => $phone,
            'code' => '1234',
            'status' => 'OK',
            'isblocked' => false,
            'sms_id' => '1234',
            'cost' => '1',
            'ip' => request()->ip(),
        ]);

        $sms_confirmation->save();

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->post($this->url, $this->data['error_code']);

        $user_after = User::where('id', '=', $user->id)->first();

        $this->assertFalse($user_after->phone_confirmed);

        $response
            ->assertStatus($this->data['error_code']['expected_status'])
            ->assertJsonStructure($this->data['error_code']['expected_structure']);
    }

    public function test_sms_success()
    {
        $phone = $this->faker->unique()->e164PhoneNumber();

        $user = User::create([
            'name' => 'test',
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('123456789'),
            'phone_confirmed' => false,
            'phone' => $phone,
            'code' => '1234',
        ]);

        $sms_confirmation = SmsConfirmation::create([
            'user_id' => $user->id,
            'phone' => $phone,
            'code' => '1234',
            'status' => 'OK',
            'isblocked' => false,
            'sms_id' => '1234',
            'cost' => '1',
            'ip' => request()->ip(),
        ]);

        $sms_confirmation->save();

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->post($this->url, $this->data['success']);

        $user_after = User::where('id', '=', $user->id)->first();

        $this->assertTrue($user_after->phone_confirmed);

        $response
            ->assertStatus($this->data['success']['expected_status'])
            ->assertJsonStructure($this->data['success']['expected_structure']);
    }

}
