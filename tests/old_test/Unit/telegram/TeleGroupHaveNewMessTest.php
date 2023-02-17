<?php

namespace Tests\old_test\Unit\telegram;

use App\Services\TelegramBotService;
use App\Services\TelegramComponents\FakeLogger;
use App\Services\TelegramMainBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Tests\BaseUnitTest;

class TeleGroupHaveNewMessTest extends BaseUnitTest
{
    /** @var array $data */
    protected $data;
    /** @var string $jsonData */
    protected $jsonData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new Request();
        $this->jsonData = Storage::disk('test_data')->get('unit/telegram/message_UnitBot_keks.json');
        $this->data = json_decode($this->jsonData, true)?:[];

    }

    /**
     * проверить что при входных данных дернется метод бота listen
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testWork()
    {
        //todo тестировать попадание сырых данных через сервис в определенные команды для обработки
        // с помощью фейкового обработчика логирования
        $botService = $this->app->make(TelegramMainBotService::class);
        $botService->run(config('telegram_bot.bot.botName'), $this->jsonData);
        $result = $this->getTestHandler()->hasRecord('Received update:','debug');
        $this->assertTrue($result);
    }

}
