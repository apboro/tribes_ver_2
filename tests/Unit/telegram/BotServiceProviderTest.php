<?php

namespace Tests\Unit\telegram;

use App\Providers\TelegramBotServiceProvider;

use App\Repositories\Telegram\TelegramConnectionRepositoryContract;
use App\Services\Telegram\BotInterface\BotContract;
use App\Services\Telegram\MainBot;
use App\Services\Telegram\MainBotCollection;
use Askoldex\Teletant\Bot;
use Tests\BaseUnitTest;


class BotServiceProviderTest extends BaseUnitTest
{
    /**
     * @var TelegramBotServiceProvider
     */
    protected $service_provider;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'telegram_bot.bot'=>[
                'token' => '1234567890:abcdef',
                'botName' => 'TestBot',
                'botFullName' => '@test_bot',
                'botId' => 1234567890,
            ],
            'telegram_bot.bot1'=>[
                'token' => '0987654321:qwerty',
                'botName' => 'TestBot1',
                'botFullName' => '@test_bot1',
                'botId' => 987654321,
            ],
        ]);
        $this->service_provider = new TelegramBotServiceProvider($this->app);
    }

    public function test_register_provider()
    {
        $this->service_provider->register();
        $this->service_provider->boot();

        $controller = $this->app->make(FakeClass::class);
        /** @var Bot $instanceBot */
        $instanceBotCollection = $controller->getMainBotCollection();
        $this->assertTrue(method_exists($instanceBotCollection, 'add'),
            'класс коллекции ботов метод add()');
        $this->assertTrue(method_exists($instanceBotCollection, 'getBotByName'),
            'класс коллекции ботов метод getBotByName()');
        $instanceBotCollection->getBotByName('TestBot');
        $this->assertInstanceOf(
            MainBot::class,
            $instanceBotCollection->getBotByName('TestBot'),
            'Не добавился первый бот'
        );
        $this->assertInstanceOf(
            MainBot::class,
            $instanceBotCollection->getBotByName('TestBot'),
            'Не добавился второй бот'
        );
    }
}

final class FakeClass {

    private TelegramConnectionRepositoryContract $telegramRepo;
    private MainBotCollection $mainBotCollection;

    public function __construct(
        TelegramConnectionRepositoryContract $telegramRepo,
        MainBotCollection $mainBotCollection
    )
    {
        $this->telegramRepo = $telegramRepo;
        $this->mainBotCollection = $mainBotCollection;
    }

    /**
     * @return TelegramConnectionRepositoryContract
     */
    public function getTelegramRepo(): TelegramConnectionRepositoryContract
    {
        return $this->telegramRepo;
    }

    /**
     * @return MainBotCollection
     */
    public function getMainBotCollection(): MainBotCollection
    {
        return $this->mainBotCollection;
    }
}
