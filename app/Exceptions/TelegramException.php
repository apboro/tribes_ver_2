<?php

namespace App\Exceptions;

use App\Services\TelegramLogService;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramException extends Exception
{
    use PrettyArrayToString;

    protected array $context = [];

    /**
     * @param string $message
     * @param ?array $context
     * @throws Exception
     */
    public function __construct($message = "", $context = [], $previous = null)
    {
        $botName = $this->context['botName'] ?? '';
        $this->context = $context;
        $this->message = $botName . ":" . $message;
        parent::__construct($message, $code = 511, $previous);
    }

    public function report()
    {
        try {
            $data = [
                'botName' => $this->context['botName'] ?? '',
                'description' => $this->getMessage(),

                'file' => $this->getFile(),
                'line' => $this->getLine(),
                'code' => $this->getCode(),
                'context' => $this->getContext(),

            ];

            Http::post('https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN') . '/sendMessage', [
                'chat_id' => env('TELEGRAM_LOG_CHAT', 507752964),
                'text' => (string)view('telegram.report', $data),
                'parse_mode' => 'html'
            ]);
            $data  = array_merge($data, ['context' => $this->getContext()]);
            /** channel('telegram-bot-log') */
            Log::debug($this->message, $data);
            if (env('LOG_LEVEL') == 'debug') {
                Log::error(
                    $this->getExceptionTraceAsString($this) . PHP_EOL
                );
            }
        } catch (Exception $e) {
            TelegramLogService::staticSendLogMessage('Ошибка' . $e->getLine() . ' : ' . $e->getMessage() . ' : ' . $e->getFile());
        }
    }

    private function getContext()
    {
        return json_encode($this->context, JSON_PRETTY_PRINT);
    }
}
