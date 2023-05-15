<?php

namespace Tests\Feature\Api\v3;

use App\Models\Community;
use App\Models\Knowledge\Knowledge;
use Illuminate\Support\Str;
use Tests\TestCase;

class KnowledgeControllerTest extends TestCase
{
    private array $url = [
        'default' => 'api/v3/knowledge',
        'bindToCommunity' => 'api/v3/knowledge/bind-communities',
    ];

    private array $statuses = [
        'unauthorized' => 401,
        'success' => 200,
        'unprocessable' => 422,
    ];

    private array $structures = [
        'messageCode' => ['message','code'],
        'default' => [
            'data' => [
                'id',
                'name',
                'uri_hash',
                'updated_at',
                'questions_count',
            ]
        ],
        'list' => [
            'data' => [[
                'id',
                'name',
                'uri_hash',
                'updated_at',
                'questions_count',
            ]]
        ],
    ];

    public function test_knowledge_store_unauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->post($this->url['default']);

        $response->assertStatus($this->statuses['unauthorized'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_knowledge_store_empty_required_data()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['default']);

        $response->assertStatus($this->statuses['unprocessable'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_knowledge_store_success()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['default'],
            [
                'knowledge_name' => Str::random(10),
            ]);

        $decodedResponse = json_decode($response->getContent());

        Knowledge::query()->where('id', $decodedResponse->data->id)->delete();

        $response->assertStatus($this->statuses['success'])->assertJsonStructure($this->structures['default']);
    }

    public function test_knowledge_list_unauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['default']);

        $response->assertStatus($this->statuses['unauthorized'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_knowledge_list_success()
    {
        $knowledgeIds = [];

        for ($i=0; $i < 3; ++$i) {
            /** @var Knowledge $knowledge */
            $knowledge = Knowledge::query()->create([
                'owner_id' => $this->custom_user->id,
                'name' => Str::random(10),
                'uri_hash' => Str::uuid(),
            ]);
            $knowledgeIds[] = $knowledge->id;
        }

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['default']);

        Knowledge::query()->whereIn('id', $knowledgeIds)->delete();

        $response->assertStatus($this->statuses['success'])->assertJsonStructure($this->structures['list']);
    }

    public function test_knowledge_show_unauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url['default'] . '/1');

        $response->assertStatus($this->statuses['unauthorized'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_knowledge_show_success()
    {
        /** @var Knowledge $knowledge */
        $knowledge = Knowledge::query()->create([
            'owner_id' => $this->custom_user->id,
            'name' => Str::random(10),
            'uri_hash' => Str::uuid(),
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->get($this->url['default'] . "/$knowledge->id");

        $knowledge->delete();

        $response->assertStatus($this->statuses['success'])->assertJsonStructure($this->structures['default']);
    }

    public function test_knowledge_update_unauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->put($this->url['default'] . '/1');

        $response->assertStatus($this->statuses['unauthorized'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_knowledge_update_empty_required_data()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->url['default'] . '/1');

        $response->assertStatus($this->statuses['unprocessable'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_knowledge_update_success()
    {
        /** @var Knowledge $knowledge */
        $knowledge = Knowledge::query()->create([
            'owner_id' => $this->custom_user->id,
            'name' => Str::random(10),
            'uri_hash' => Str::uuid(),
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->put($this->url['default'] . "/$knowledge->id",
            [
                'knowledge_name' => Str::random(10),
            ]);

        $knowledge->delete();

        $response->assertStatus($this->statuses['success'])->assertJsonStructure($this->structures['default']);
    }

    public function test_knowledge_delete_unauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->delete($this->url['default'] . '/1');

        $response->assertStatus($this->statuses['unauthorized'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_knowledge_delete_success()
    {
        /** @var Knowledge $knowledge */
        $knowledge = Knowledge::query()->create([
            'owner_id' => $this->custom_user->id,
            'name' => Str::random(10),
            'uri_hash' => Str::uuid(),
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->delete($this->url['default'] . "/$knowledge->id");

        $knowledge->delete();

        $response->assertStatus($this->statuses['success'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_knowledge_bindToCommunity_unauthorized()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url['bindToCommunity']);

        $response->assertStatus($this->statuses['unauthorized'])->assertJsonStructure($this->structures['messageCode']);
    }

    public function test_knowledge_bindToCommunity_empty_required_data()
    {
        $response1 = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['bindToCommunity'],
            [
                'community_ids' => [1,2,3],
                'knowledge_id' => null,
            ])->assertJsonStructure($this->structures['messageCode']);
        $response2 = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['bindToCommunity'],
            [
                'community_ids' => null,
                'knowledge_id' => 1,
            ])->assertJsonStructure($this->structures['messageCode']);

        $this->assertTrue($response1->status() === 422 && $response2->status() === 422);
    }

    public function test_knowledge_bindToCommunity_success()
    {
        $community = Community::query()->create([
            'owner' => $this->custom_user->id,
        ]);

        /** @var Knowledge $knowledge */
        $knowledge = Knowledge::query()->create([
            'owner_id' => $this->custom_user->id,
            'name' => Str::random(10),
            'uri_hash' => Str::uuid(),
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->custom_token,
        ])->post($this->url['bindToCommunity'],
            [
                'community_ids' => [$community->id],
                'knowledge_id' => $knowledge->id,
            ]);

        $knowledge->delete();
        $community->delete();

        $response->assertStatus($this->statuses['success'])->assertJsonStructure($this->structures['messageCode']);
    }
}
