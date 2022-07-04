<?php

namespace App\Services\Telegram;

use Askoldex\Teletant\Bot;


class KnowledgeBot extends Bot
{
    public function __construct($settings)
    {
        parent::__construct($settings);
    }
}