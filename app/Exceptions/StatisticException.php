<?php

namespace App\Exceptions;

use App\Services\TelegramLogService;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StatisticException extends Exception
{
    use PrettyArrayToString;

    public $status = 425;
    private array $context;
    private TelegramLogService $telegramLogService;

    public function __construct($message = "", $context = [])
    {
        parent::__construct($message, $this->status, null);
        $this->context = $context;
        $this->telegramLogService = app()->make(TelegramLogService::class);
    }

    public function report()
    {
        Log::debug($this->message,$this->context);
        if(env('APP_ENV') !== 'testing') {
            $this->telegramLogService->sendLogMessage($this->message);
        }
    }

    public function getContext()
    {
       return $this->context;
    }
}