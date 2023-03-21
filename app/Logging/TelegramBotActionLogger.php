<?php

namespace App\Logging;
use Monolog\Logger;
class TelegramBotActionLogger
{
    public function __invoke(array $config){
        $logger = new Logger("MySQLLoggingHandler");
        return $logger->pushHandler(new TelegramBotActionHandler());
    }
}