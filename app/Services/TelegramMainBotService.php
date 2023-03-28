<?php

namespace App\Services;

use App\Exceptions\TelegramException;
use App\Models\Community;
use App\Services\Telegram\BotInterface\TelegramMainBotServiceContract;
use App\Services\Telegram\MainBotCollection;
use App\Services\Telegram\MainComponents\MainBotCommands;
use App\Services\Telegram\MainComponents\MainBotEvents;
use App\Services\Telegram\MainComponents\MessageObserver;
use App\Services\Telegram\MainComponents\TelegramMidlwares;
use Exception;

class TelegramMainBotService implements TelegramMainBotServiceContract
{
    protected MainBotCollection $botCollect;
    public MainBotCommands $mainBotCommands;
    protected TelegramMidlwares $middleware;
    private TelegramLogService $telegramLogService;


    public function __construct(
        MainBotCollection $botCollection,
        MainBotCommands $mainBotCommands,
        TelegramMidlwares $middleware,
        TelegramLogService $telegramLogService
    ) {
        $this->botCollect = $botCollection;
        $this->mainBotCommands = $mainBotCommands;
        $this->middleware = $middleware;
        $this->telegramLogService = $telegramLogService;
    }

    protected function getCommandsForBot(string $nameBot): MainBotCommands
    {
        $this->mainBotCommands->initBot($this->botCollect->getBotByName($nameBot));
        return $this->mainBotCommands;
    }

    public function getApiCommandsForBot(string $nameBot)
    {
        return $this->botCollect->getBotByName($nameBot)->getExtentionApi();
    }

    public function run(string $nameBot, string $data)
    {

        try {
            $object = json_decode($data, false) ?: null;
            if (!isset($object->channel_post)) {
                $this->middleware->bootMidlwares($this->botCollect->getBotByName($nameBot));
            }
            $events = new MainBotEvents($this->botCollect->getBotByName($nameBot), $object);
            $events->initEventsMainBot();

            $events->initEventsMainBot([
                [
                    'isNewReplay' => [app('knowledgeObserver'), 'handleAuthorReply'],
                    'isNewTextMessage' => [app('knowledgeObserver'), 'detectUserQuestion'],
                    'isNewForwardMessageInBotChat' => [app('knowledgeObserver'), 'detectForwardMessageBotQuestion', ['botName' => $nameBot]],
                ]
            ]);

            $events->initEventsMainBot([[
                'isNewTextMessage' => [app('messageObserver'), 'handleUserMessage'],
            ]]);
            $this->getCommandsForBot($nameBot)->initCommand();
            // Для локальной разработки - метод polling раскоментить, метод listen закоментить. Запустить php artisan teelgram:bot:run. 
            // Если локально используется бот к которому прокинут хук, его необходимо отключить.
            //$this->botCollect->getBotByName($nameBot)->polling();
            $this->botCollect->getBotByName($nameBot)->listen($data);
        } catch (Exception | TelegramException $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . ' : ' . $e->getMessage() . ' : ' . $e->getFile() . $e->getLine());
        }
    }

    public function sendLogMessage(string $text)
    {
        $this->getApiCommandsForBot(config('telegram_bot.bot.botName'))->sendMessage([
            'chat_id'        => env('TELEGRAM_LOG_CHAT'),
            'text'           => $text,
            'parse_mode'     => 'HTML'
        ]);
    }

    public function sendMessageFromBot(string $botName, int $chatId, string $textMessage, bool $preview = false, array $keyboard = [])
    {
        if ($this->botCollect->hasBotByName($botName)) {
            $this->getApiCommandsForBot($botName)->sendMess($chatId, $textMessage, $preview, $keyboard);
        }
    }

    public function sendMessageFromBotWithTariff(string $botName, int $chatId, string $textMessage, Community $community)
    {
        if ($this->botCollect->hasBotByName($botName)) {
            $this->getCommandsForBot($botName)->sendMessageFromBotWithTariff($chatId, $textMessage, $community);
        }
    }

    public function sendDonateMessage(string $botName, int $chatId, int $donateId)
    {
        $this->getCommandsForBot($botName)->sendDonateMessage($chatId, $donateId);
    }

    public function sendTariffMessage(string $botName, Community $community)
    {
        $this->getCommandsForBot($botName)->sendTariffMessage($community);
    }

    public function kickUser(string $botName, int $userId, int $chatId)
    {
        $this->getApiCommandsForBot($botName)->kickUser($userId, $chatId);
    }

    public function unKickUser(string $botName, int $userId, int $chatId)
    {
        $this->getApiCommandsForBot($botName)->unKickUser($userId, $chatId);
    }

    public function getChatMemberCount(string $botName, int $chatId)
    {
        return $this->getApiCommandsForBot($botName)->getChatCount($chatId);
    }

    public function getChatAdministratorsList(string $botName, int $chatId)
    {
        return $this->getApiCommandsForBot($botName)->getChatAdministratorsList($chatId);
    }

    public function hasBotByName($botName): bool
    {
        return $this->botCollect->hasBotByName($botName);
    }

    public static function staticGetChatMemberCount(string $botName, int $chatId)
    {
        $service = new self(
            app(MainBotCollection::class),
            app(MainBotCommands::class),
            app(TelegramMidlwares::class),
            app(TelegramLogService::class)
        );

        return $service->getChatMemberCount($botName, $chatId);
    }

    public static function staticGetChatAdministratorsList(string $botName, int $chatId)
    {
        $service = new self(
            app(MainBotCollection::class),
            app(MainBotCommands::class),
            app(TelegramMidlwares::class),
            app(TelegramLogService::class)
        );

        return $service->getChatAdministratorsList($botName, $chatId);
    }

    public function muteUser(string $botName, int $userId, int $chatId, int $time)
    {
        $this->getApiCommandsForBot($botName)->muteUser($userId, $chatId, $time);
    }
    public function deleteUserMessage(string $botName, int $message_id, int $chatId)
    {
        $this->getApiCommandsForBot($botName)->deleteUserMessage($message_id, $chatId);
    }
}
