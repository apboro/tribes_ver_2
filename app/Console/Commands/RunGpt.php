<?php

namespace App\Console\Commands;

use App\Jobs\SendTeleMessageToChatFromBot;
use App\Models\TelegramMessage;
use App\Models\TelegramChatTheme;
use App\Models\TelegramConnection;
use App\Services\TelegramLogService;
use App\Services\Gpt\ApiGpt;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class RunGpt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:gpt';

    private TelegramLogService $telegramLogService;
    private ApiGpt $apiGpt;


    public function __construct(TelegramLogService $telegramLogService, ApiGpt $apiGpt)
    {
        parent::__construct();
        $this->telegramLogService = $telegramLogService;
        $this->apiGpt = $apiGpt;
    }

    private function prepareQuestion(Collection $tgMessages): string
    {
        $messageList = '';
        foreach ($tgMessages as $message) {
            $messageList .= 'Сообщение: "' . $message->text . "\"\n";
        }

        return 'Очисть контекст! Ниже будет текст переписки в чате. Определи 3 основные темы, о которых общались в данной переписке. ' .
            'Напиши только 3 темы, каждую с новой строки. После темы в скобках укажи количество сообщений по данной теме. ' .
            'Если не можешь написать 3 темы - пиши меньше. ' . 
            'Если сообщения не содержат темы - напиши слово "нет" (и ничего кроме него). Текст переписки: ' . "\n\n" . $messageList;
    }

    private function prepareTheme(string $theme): array
    {
        $quantity = 1;
        $findQuantity = preg_match('/^(.*)\(([0-9]+)\)$/', $theme, $result);
        if ($findQuantity) {
            $quantity = (int)$result[2];
            $theme = $result[1];
        }

        $themeWithNumbers = preg_match('/^([1-9]+\.)(.*)$/', $theme, $result);
        if ($themeWithNumbers) {
            $theme = $result[2];
        }

        $theme = trim($theme);

        return [$theme, $quantity];
    }

    private function saveThemes(string $answer, int $chatId): void
    {
        $themes = explode("\n", $answer);
        foreach ($themes as $theme) {
            [$theme, $quantity] = $this->prepareTheme($theme);
            if (empty($theme) || $theme === 'нет') {
                continue;
            }
            TelegramChatTheme::add($chatId, $theme, $quantity);
        }
    }

    public function handle()
    {
        try {
            $startTime = date('Y-m-d H:i:s', time() - config('gpt.themesIntervalHours') * 3600);
            $endTime = date('Y-m-d H:i:s', time());
            $chatIds = TelegramConnection::getAllChats();

            foreach ($chatIds as $chatId) {
                $tgMessages = TelegramMessage::findMessagesByTimePeriod($chatId,  $startTime,  $endTime);
                if (count($tgMessages) > 0) {
                    $botQuestion = $this->prepareQuestion($tgMessages);
                    $botAnswer = $this->apiGpt->run($botQuestion);
                    $this->saveThemes($botAnswer, $chatId);
                    usleep(config('gpt.waitBetweenRequests')); 
                }

                SendTeleMessageToChatFromBot::dispatch(
                        config('telegram_bot.bot.botName'), 
                        $chatId, 
                        TelegramChatTheme::getMessageWithThemesByData($chatId, date('Y-m-d')));


            }

            return 1;
        } catch (\Exception $e) {
            Log::alert('Исключение при запросе к ChatGPT', ['exception' => $e]);
            $this->telegramLogService->sendLogMessage('Ошибка:' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
        return 0;
    }
}
