<?php

namespace Tests;

use App\Models\Community;
use App\Models\TelegramConnection;
use App\Models\User;
use Askoldex\Teletant\Context;
use Askoldex\Teletant\Entities\Message;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Monolog\Logger;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Monolog\Handler\TestHandler;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;
    use WithFaker;

    /** @var Application */
    protected $app;

    /** @var Logger $testHandler */
    protected $logger;

    /** @var User */
    protected $custom_user;

    /** @var string */
    protected $custom_token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock(Context::class)
            ->shouldReceive('reply')
            ->andReturn(new Message($this->getDataFromFile('telegram/text_message.json')));

        Http::fake(['*' => function ($request, $options) {
            /** @var Request $request */
            Log::debug('post http request', [
                'url' => $request->url(),
                'data' => $request->data(),
            ]);
            return Http::response();
        }]);

        $this->app = app();

        $channel = Log::channel('testing');

        $this->logger = $channel->getLogger();

        $this->createUserForTest();
    }

    /*protected function tearDown(): void
    {
        parent::tearDown();
    }*/

    protected function refreshTestDatabase()
    {
         if (! RefreshDatabaseState::$migrated) {
        // $this->artisan('db:wipe --database=knowledge');
          $this->artisan('migrate:fresh', $this->migrateFreshUsing());

        //  $this->app[Kernel::class]->setArtisan(null);

           RefreshDatabaseState::$migrated = true;
          }

        //$this->beginDatabaseTransaction();
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function getDataFromFile($name = '', $asJson = false)
    {
        $jsonData = Storage::disk('test_data')->get("feature/$name");
        if ($asJson) {
            return $jsonData;
        }
        return json_decode($jsonData, true) ?: [];
    }

    protected function getTestHandler(): TestHandler
    {
        $handlers = $this->logger->getHandlers();
        return current($handlers);
    }

    public function createUserForTest(array $parameters = [])
    {
        do {
            $email = $this->faker->unique()->safeEmail();
            $is_exists = User::where('email', '=', $email)->first();
        } while ($is_exists);

        $this->custom_user = User::create([
            'name' => (!empty($parameters['name']) ? $parameters['name'] : ''),
            'email' => $email,
            'password' => bcrypt((!empty($parameters['password']) ? $parameters['password'] : 123456)),
            'phone_confirmed' => (!empty($parameters['phone_confirmed']) ? $parameters['phone_confirmed'] : false),
        ]);

        $this->custom_token = $this->custom_user->createToken('api-token')->plainTextToken;
    }

    /**
     * @param ?array $data
     *
     * @return array|mixed|string
     */
    protected function prepareDBCommunity(?array $data = [])
    {
        /** @var User $user */
        $user = User::factory()->createItem([
            'name' => 'Test Testov',
            'email' => 'test-dev@webstyle.top',
        ]);
        /** @var TelegramConnection $connection */
        $connection = TelegramConnection::factory()->botAdmin()->groupConn()->active()
            ->create([
                'chat_id' => 1,
                'chat_title' => 'Group for Test Testov',
                'user_id' => $user->id,
                'telegram_user_id' => 333,
            ]);

        /** @var Community $community */
        $community = Community::factory()->for($connection, 'connection')->create([
            'owner' => $user->id,
            'title' => 'Group for Test Testov',
        ]);
        $community->generateHash();
        $community->updateQuietly(['hash' => $community->hash]);
        return array_merge($data, [
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ],
            'telegram_connection' => [
                'id' => $connection->id,
            ],
            'community' => $community->getAttributes(),
            'community_object' => $community,
        ]);
    }
}
