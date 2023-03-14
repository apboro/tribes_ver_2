<?php

namespace Tests;

use App\Models\Community;
use App\Models\Models\Tag;
use App\Models\TelegramConnection;
use App\Models\TelegramUser;
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

    protected User $custom_user;
    protected TelegramUser $custom_telegram_user;
    protected TelegramConnection $custom_telegram_connection;
    protected Community $custom_community;
    protected string $custom_token;

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
        $this->createTelegramUserForTest();
        $this->createTelegramConnectionForTest();
        $this->createCommunityForTest();
    }

    /*protected function tearDown(): void
    {
        parent::tearDown();
    }*/

    protected function refreshTestDatabase()
    {
         if (! RefreshDatabaseState::$migrated) {
         //$this->artisan('db:wipe --database=knowledge');
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

    public function createTelegramUserForTest(array $parameters = [])
    {
//        $this->createUserForTest();

        $this->custom_telegram_user = TelegramUser::create([
            'user_id' => !empty($parameters['user_id']) ? $parameters['user_id'] : $this->custom_user->id,
            'telegram_id' => !empty($parameters['telegram_id']) ? $parameters['telegram_id'] : rand(10000000, 90000000),
            'auth_date' => !empty($parameters['auth_date']) ? $parameters['auth_date'] : rand(1000000, 9000000),
            'first_name' => !empty($parameters['first_name']) ? $parameters['first_name'] : 'Test TU',
            'last_name' => !empty($parameters['last_name']) ? $parameters['last_name'] : 'Test TU',
            'photo_url' => !empty($parameters['photo_url']) ? $parameters['photo_url'] : 'Test TU',
            'user_name' => !empty($parameters['user_name']) ? $parameters['user_name'] : 'tester',
        ]);
    }

    public function createTelegramConnectionForTest(array $parameters = [])
    {

        $this->custom_telegram_connection = TelegramConnection::create([
            'user_id' => $this->custom_user->id,
            'telegram_user_id' => $this->custom_telegram_user->telegram_id,
            'chat_id' => !empty($parameters['chat_id']) ? $parameters['chat_id'] : "-" . rand(700000000, 799999999),
            'chat_title' => !empty($parameters['chat_title']) ? $parameters['chat_title'] : $this->faker->text(80),
            'chat_type' => !empty($parameters['chat_type']) ? $parameters['chat_type'] : 'channel',
            'isAdministrator' => !empty($parameters['isAdministrator']) ? $parameters['isAdministrator'] : true,
            'botStatus' => !empty($parameters['botStatus']) ? $parameters['botStatus'] : 'administrator',
            'isActive' => !empty($parameters['isActive']) ? $parameters['isActive'] : array_rand([true, false]),
            'hash' => !empty($parameters['hash']) ? $parameters['hash'] : md5('test hash'),
            'isChannel' => !empty($parameters['isChannel']) ? $parameters['isChannel'] : false,
            'isGroup' => !empty($parameters['isGroup']) ? $parameters['isGroup'] : true,
            'status' => !empty($parameters['status']) ? $parameters['status'] : 'init',
        ]);

    }

    public function createCommunityForTest(array $parameters = [])
    {

        $this->custom_community = Community::create([
            'owner' => !empty($parameters['owner']) ? $parameters['owner'] : $this->custom_user->id,
            'title' => !empty($parameters['title']) ? $parameters['title'] : 'test connection',
            'connection_id' => !empty($parameters['connection_id']) ? $parameters['connection_id'] : $this->custom_telegram_connection->id,
        ]);
        for($i=0;$i<3;$i++){
            $tag = Tag::create([
                'user_id'=>!empty($parameters['owner']) ? $parameters['owner'] : $this->custom_user->id,
                'name'=>$this->faker->word
            ]);
            $this->custom_community->tags()->attach($tag);
        }

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
