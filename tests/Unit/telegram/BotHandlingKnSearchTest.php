<?php

namespace Tests\Unit\telegram;

use App\Services\TelegramMainBotService;
use Askoldex\Teletant\Context;
use Askoldex\Teletant\Entities\Message;
use Tests\BaseUnitTest;

class BotHandlingKnSearchTest extends BaseUnitTest
{
    public function testHandleQaCommand()
    {
        $this->mock(Context::class)
            ->shouldReceive('replyHTML')
            ->andReturn(new Message(['text' => 'test message']));
        $data = $this->getDataFromFile('telegram/message_qa_command.json',true);
        $botService = $this->app->make(TelegramMainBotService::class);
        $botService->run(config('telegram_bot.bot.botName'), $data);
        $result = $this->getTestHandler()->hasRecord( 'Поиск по БЗ','debug');
        $this->assertTrue($result, "Не находит в логах Поиск по БЗ");
        $result = $this->getTestHandler()->hasRecord( ' search.maxime','debug');
        $this->assertTrue($result, "Не находит в логах search.maxime");
    }

    public function testHandleQaCommandWrong()
    {
         $this->mock(Context::class)
            ->shouldReceive('replyHTML')
            ->andReturn(new Message(['text' => 'test message']));
        $data = $this->getDataFromFile('telegram/message_qa_wrong_command.json',true);
        $botService = $this->app->make(TelegramMainBotService::class);
        $botService->run(config('telegram_bot.bot.botName'), $data);
        $result = $this->getTestHandler()->hasRecord( 'Поиск по БЗ','debug');
        $this->assertFalse($result, "Не находит в логах Поиск по БЗ");
        $result = $this->getTestHandler()->hasRecord( ' search.maxime','debug');
        $this->assertFalse($result, "Не находит в логах search.maxime");
    }
}
