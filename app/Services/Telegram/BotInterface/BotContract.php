<?php 

namespace App\Services\Telegram\BotInterface;

use App\Services\Telegram\Extention\ExtentionApi;
use Askoldex\Teletant\Exception\TeletantException;

interface BotContract
{
    public function getExtentionApi(): ExtentionApi;
    public function getToken(): string;
}