<?php

namespace Tests\Feature\Api\v3;

use App\Models\TelegramUser;
use App\Models\TelegramUserList;
use App\Repositories\TelegramUserLists\TelegramUserListsRepositry;
use Database\Seeders\TelegramUserListSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ApiTelegramUserMuteListFilterTest extends TestCase
{
    use WithFaker;

    private $url = [
        'filter_mute_list' => 'api/v3/user/mute-list',
    ];

    private $data = [
        'error_not_auth' => [
            'expected_status' => 401,
            'expected_structure' => [
                'message',
            ],
        ],
        'not_valid_data' => [
            'expected_status' => 422,
            'expected_structure' => [
                'message',
                'errors'
            ],
        ],
        'filter_success' => [
            'expected_status' => 200,
            'expected_structure' => [
                'data' =>
                    [
                        [
                            'telegram_id',
                            'user_name',
                            'first_name',
                            'last_name',
                            'block_date',
                            'communities' => [],
                            'parameter' => []
                        ]
                    ]
            ]
        ],
    ];

    public function setUp(): void
    {
        parent::setUp();
        TelegramUser::factory()->count(100)->create();
        TelegramUserList::factory()->count(50)->create();
        $seeder_instance = new TelegramUserListSeeder();
        $seeder_instance->run();
        $telegram_black_list = TelegramUserList::all();

        $communities = [];
        for ($i = 0; $i < 10; $i++) {
            $this->createCommunityForTest();
            $communities[] = $this->custom_community;
        }
        foreach ($telegram_black_list as $row) {
            DB::table('list_community_telegram_user')->insertOrIgnore([
                'telegram_id' => $row->telegram_id,
                'community_id' => $this->faker->randomElement($communities)->id,
                'type'=>$row->type
            ]);
            if ($row->type == 1 && $this->faker->boolean(70)) {
                $row->listParameters()->sync([TelegramUserListsRepositry::SPAMMER]);
            }
        }

    }

    public function test_mute_list_filter_not_auth()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['filter_mute_list']);

        $response->assertStatus($this->data['error_not_auth']['expected_status'])
            ->assertJsonStructure($this->data['error_not_auth']['expected_structure']);
    }

    public function test_mute_list_filter_not_valid_community_id()
    {

        $this->data['not_valid_data']['community_id'] = 'test';
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['filter_mute_list'], $this->data['not_valid_data']);

        $response->assertStatus($this->data['not_valid_data']['expected_status'])
            ->assertJsonStructure($this->data['not_valid_data']['expected_structure']);
    }



    public function test_mute_list_filter_user_name_success()
    {

        $this->data['filter_success']['telegram_name'] = 'test';

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['filter_mute_list'], $this->data['filter_success']);

        $response->assertStatus($this->data['filter_success']['expected_status'])
            ->assertJsonStructure($this->data['filter_success']['expected_structure']);
    }

    public function test_mute_list_filter_community_id()
    {
        $this->setUp();
        $this->data['filter_success']['community_id'] = $this->custom_community->id;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['filter_mute_list'], $this->data['filter_success']);

        $response->assertStatus($this->data['filter_success']['expected_status'])
            ->assertJsonStructure($this->data['filter_success']['expected_structure']);
    }
}
