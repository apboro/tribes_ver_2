<?php

namespace App\Exceptions;

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
    public function __construct($message = "", $context = [],$previous = null)
    {
        $botName = $this->context['botName']??'';
        $this->context = $context;
        $this->message = $botName. ":" .$message;
        parent::__construct($message, $code = 511, $previous);

    }

    public function report()
    {

        $data = [
            'botName' => $this->context['botName']??'',
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
        $data  = array_merge($data,['context' => $this->getContext()]);
            /** channel('telegram-bot-log') */
        Log::channel('single')->debug($this->message, $data);
        Log::channel('single')->error(
            $this->getExceptionTraceAsString($this) . PHP_EOL
        );


    }

    private function getContext()
    {
        return json_encode($this->context,JSON_PRETTY_PRINT);
    }

}
