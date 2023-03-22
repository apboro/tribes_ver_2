<?php

namespace App\Logging;
use Monolog\Logger;
class TelegramBotActionLogger
{
    public function __invoke(array $config){
        $logger = new Logger("TelegramBotActionHandler");
        return $logger->pushHandler(new TelegramBotActionHandler());
    }
}