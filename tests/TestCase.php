<?php

namespace Tests;

use App\Models\Community;
use App\Models\TelegramConnection;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Monolog\Logger;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Monolog\Handler\TestHandler;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    /**
     * @var Application
     */
    protected $app;
    /** @var Logger $testHandler */
    protected $logger;

    protected function setUp(): void
    {
        Http::shouldReceive('post')
            ->times()
            ->andReturn(null);
        parent::setUp();
        $this->app = app();
        $channel = Log::channel('testing');
        $this->logger = $channel->getLogger();
    }

    /*protected function tearDown(): void
    {
        parent::tearDown();
    }*/

    protected function refreshTestDatabase()
    {
        if (! RefreshDatabaseState::$migrated) {
            $this->artisan('db:wipe --database=knowledge');
            $this->artisan('migrate:fresh', $this->migrateFreshUsing());

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
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

    /**
     * @param ?array $data
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
        return array_merge($data,[
            'user' => [
                'id' => $user->id,
            ],
            'telegram_connection' => [
                'id' => $connection->id,
            ],
            'community' => [
                'id' => $community->id,
                'owner' => $community->owner,
            ],
        ]);
    }
}
