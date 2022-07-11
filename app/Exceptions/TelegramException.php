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
    public function __construct($message = "", $context = [])
    {
        $botName = $this->context['botName']??'';
        $this->context = $context;
        $this->message = $botName. ":" .$message;
        parent::__construct($message, $code = 511);

    }

    public function report()
    {

        $data = [
            'botName' => $this->context['botName']??'',
            'description' => $this->getMessage(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'code' => $this->getCode(),

        ];

        Log::channel('telegram-bot-log')->debug($this->message, $data);
        Log::channel('single')->error(
            $this->getExceptionTraceAsString($this) . PHP_EOL
        /*.$this->arrayToPrettyString($_SERVER)*/
        );

        Http::post('https://api.telegram.org/bot' . env('TELEGRAM_BOT_TOKEN') . '/sendMessage', [
            'chat_id' => env('TELEGRAM_LOG_CHAT', 507752964),
            'text' => (string)view('telegram.report', $data),
            'parse_mode' => 'html'
        ]);
    }

}
