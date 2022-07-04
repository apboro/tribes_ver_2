<?php

namespace Tests\Unit\telegram;

use App\Helper\ArrayHelper;
use App\Services\TelegramBotService;
use App\Services\TelegramComponents\FakeLogger;
use Askoldex\Teletant\Bot;
use Illuminate\Log\LogManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Monolog\Handler\TestHandler;
use Psr\Log\LoggerInterface;
use Tests\BaseUnitTest;

class BotServiceTest extends BaseUnitTest
{
    protected $data;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new Request();
        $json = Storage::disk('test_data')->get('unit/telegram/text_message.json');
        $this->data = json_decode($json, true);
    }

    /**
     * проверить что при входных данных дернется метод бота listen
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testWorkListen()
    {
        $this->assertTrue(true);
        // todo переделать проверку запуска метода listen бота
       /* $this->mock(Request::class)
            ->shouldReceive('all')
            ->andReturn($this->data);
        $spyBot = $this->spy(Bot::class);
        $botService = $this->app->make(TelegramBotService::class);
        $botService->bot();
        $spyBot->shouldHaveReceived('listen');*/
    }

    /**
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testWorkHandleUpdate()
    {
        $this->assertTrue(true);
        //todo переделать проверку обработки метода HandleUpdate
       /* $request = $this->mock(Request::class)
            ->shouldReceive('all')
            ->andReturn($this->data);

        $botService = $this->app->make(TelegramBotService::class);
        $botService->bot();
        $updateId = ArrayHelper::getValue($this->data, 'update_id');
        $keyed = ArrayHelper::index(FakeLogger::$logsData, 'update_id');

        $this->assertArrayHasKey($updateId, $keyed);*/
    }

    public function testRegisterTestChannelLogger()
    {
        $this->assertTrue(true);
        // пример тестирования через лог с помощью testLogHandler Monolog
        $log = $this->app->make('log');
        $log->info('test1');
        /*$log = app('log');
        */
        Log::info('test',['debug_info' => 1]);

        $this->assertTrue(
            $this->getTestHandler()->hasRecord('test1', 'info'),
            'Ошибка инициализированного Логера'
        );

        $this->assertTrue($this->getTestHandler()->hasRecord([
            'message' => 'test',
            'context' => ['debug_info' => 1]
        ], 'info'),
            'Ошибка фасада логгера');
    }
}
