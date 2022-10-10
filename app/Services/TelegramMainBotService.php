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
        try {
            $this->mainBotCommands->initBot($this->botCollect->getBotByName($nameBot));
            return $this->mainBotCommands;
        } catch (Exception | TelegramException $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . ' : ' . $e->getMessage() . ' : ' . $e->getFile() . $e->getLine());
        }
    }

    public function getApiCommandsForBot(string $nameBot)
    {
        try {
            return $this->botCollect->getBotByName($nameBot)->getExtentionApi();
        } catch (Exception | TelegramException $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . ' : ' . $e->getMessage() . ' : ' . $e->getFile() . $e->getLine());
        }
    }

    public function run(string $nameBot, string $data)
    {
        try {
            $object = json_decode($data, false) ?: null;
            if (!isset($object->channel_post)) {
                $this->middleware->bootMidlwares($this->botCollect->getBotByName($nameBot));
            }
            // $events = new MainBotEvents($this->botCollect->getBotByName($nameBot), $object);
            // $events->initEventsMainBot();
            // $events->initEventsMainBot([[
            //     'isNewReplay' => [app('knowledgeObserver'), 'handleAuthorReply'],
            //     'isNewTextMessage' => [app('knowledgeObserver'), 'detectUserQuestion'],
            //     'isNewForwardMessageInBotChat' => [
            //         app('knowledgeObserver'),
            //         'detectForwardMessageBotQuestion',
            //         ['botName' => $nameBot]
            //     ],
            // ]]);
            // $events->initEventsMainBot([[
            //     'isNewTextMessage' => [app('messageObserver'), 'handleUserMessage'],
            // ]]);
            $this->getCommandsForBot($nameBot)->initCommand();
            // $this->botCollect->getBotByName($nameBot)->polling();
            $this->botCollect->getBotByName($nameBot)->listen($data);
        } catch (Exception | TelegramException $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . ' : ' . $e->getMessage() . ' : ' . $e->getFile() . $e->getLine());
        }
    }

    public function sendLogMessage(string $text)
    {
        try {
            $this->getApiCommandsForBot(config('telegram_bot.bot.botName'))->sendMessage([
                'chat_id'        => env('TELEGRAM_LOG_CHAT'),
                'text'           => $text,
                'parse_mode'     => 'HTML'
            ]);
        } catch (Exception | TelegramException $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . ' : ' . $e->getMessage() . ' : ' . $e->getFile() . $e->getLine());
        }
    }

    public function sendMessageFromBot(string $botName, int $chatId, string $textMessage, bool $preview = false, array $keyboard = [])
    {
        try {
            if ($this->botCollect->hasBotByName($botName)) {
                $this->getApiCommandsForBot($botName)->sendMess($chatId, $textMessage, $preview, $keyboard);
            }
        } catch (Exception | TelegramException $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . ' : ' . $e->getMessage() . ' : ' . $e->getFile() . $e->getLine());
        }
    }

    public function sendMessageFromBotWithTariff(string $botName, int $chatId, string $textMessage, Community $community)
    {
        try {
            if ($this->botCollect->hasBotByName($botName)) {
                $this->getCommandsForBot($botName)->sendMessageFromBotWithTariff($chatId, $textMessage, $community);
            }
        } catch (Exception | TelegramException $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . ' : ' . $e->getMessage() . ' : ' . $e->getFile() . $e->getLine());
        }
    }

    public function sendDonateMessage(string $botName, int $chatId, int $donateId)
    {
        try {
            $this->getCommandsForBot($botName)->sendDonateMessage($chatId, $donateId);
        } catch (Exception | TelegramException $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . ' : ' . $e->getMessage() . ' : ' . $e->getFile() . $e->getLine());
        }
    }

    public function sendTariffMessage(string $botName, Community $community)
    {
        try {
            $this->getCommandsForBot($botName)->sendTariffMessage($community);
        } catch (Exception | TelegramException $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . ' : ' . $e->getMessage() . ' : ' . $e->getFile() . $e->getLine());
        }
    }

    public function kickUser(string $botName, int $userId, int $chatId)
    {
        try {
            $this->getApiCommandsForBot($botName)->kickUser($userId, $chatId);
        } catch (Exception | TelegramException $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . ' : ' . $e->getMessage() . ' : ' . $e->getFile() . $e->getLine());
        }
    }

    public function unKickUser(string $botName, int $userId, int $chatId)
    {
        try {
            $this->getApiCommandsForBot($botName)->unKickUser($userId, $chatId);
        } catch (Exception | TelegramException $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . ' : ' . $e->getMessage() . ' : ' . $e->getFile() . $e->getLine());
        }
    }

    public function getChatMemberCount(string $botName, int $chatId)
    {
        try {
            return $this->getApiCommandsForBot($botName)->getChatCount($chatId);
        } catch (Exception | TelegramException $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . ' : ' . $e->getMessage() . ' : ' . $e->getFile() . $e->getLine());
        }
    }

    public function getChatAdministratorsList(string $botName, int $chatId)
    {
        try {
            return $this->getApiCommandsForBot($botName)->getChatAdministratorsList($chatId);
        } catch (Exception | TelegramException $e) {
            $this->telegramLogService->sendLogMessage('Ошибка:' . ' : ' . $e->getMessage() . ' : ' . $e->getFile() . $e->getLine());
        }
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
}
